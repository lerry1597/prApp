<?php

namespace App\Filament\Pages\App;

use App\Constants\DocumentConstant;
use App\Constants\PrStatusConstant;
use App\Models\PrHeader;
use App\Models\PrDetail;
use App\Models\Item;
use App\Models\Department;
use App\Models\ItemCategory;
use App\Models\ApprovalWorkflow;
use App\Models\ApprovalStep;
use App\Models\PrLog;
use App\Models\PrHistory;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use App\Service\DateService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PurchaseRequisition extends Page
{

    protected static ?string $navigationLabel = 'Form Pengajuan PR';
    protected static ?string $title = 'Formulir Permintaan';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-pencil-square';
    protected string $view = 'filament.app.pages.purchase-requisition';

    public ?array $data = [];
    public string $sequenceNo = '';
    public string $departmentName = '';
    public string $vesselName = '';
    public string $companyCode = '';
    public string $documentNo = '';
    public string $clientDateTime = '';
    public string $needs = 'Mesin';

    public array $items = [];
    public array $itemCategories = [];
    public bool $showPreviewModal = false;

    public function getHeading(): string
    {
        return '';
    }

    public function mount(): void
    {
        $this->sequenceNo = $this->generateSequenceNo();

        $user = auth()->user();
        $details = $user?->detailsUser;
        $vessel = $user?->vessel;

        $this->vesselName = $vessel?->name ?? 'UNKNOWN VESSEL';
        $this->companyCode = $vessel?->company?->code ?? 'UNKNOWN';
        $this->departmentName = $details?->department?->name ?? 'Kru Kapal';

        $now = app(DateService::class)->getCurrentDate();
        $month = $now->format('m');
        $year = $now->format('Y');
        $vesselCode = $vessel?->code ?? 'VSSL';
        $this->documentNo = "PR-{$vesselCode}-{$this->companyCode}-{$month}-{$year}-{$this->sequenceNo}";

        $this->itemCategories = ItemCategory::pluck('name', 'id')->toArray();
        $this->items = [
            ['item_category_id' => '', 'type' => '', 'size' => '', 'quantity' => '', 'unit' => '', 'remaining' => '']
        ];
    }

    public function addItem(): void
    {
        $this->items[] = [
            'item_category_id' => '',
            'type' => '',
            'size' => '',
            'quantity' => '',
            'unit' => '',
            'remaining' => '',
        ];

        $this->js("
            setTimeout(function() {
                var el = document.querySelector('.pr-table-scroll');
                if (el) el.scrollTop = el.scrollHeight;
            }, 50);
        ");
    }

    public function removeItem(int $index): void
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);

        if (empty($this->items)) {
            $this->addItem();
        }
    }

    protected function generateSequenceNo(): string
    {
        $latestNo = PrDetail::all()->max(fn($detail) => (int)$detail->no);
        $nextNo = $latestNo ? (int)$latestNo + 1 : 1;
        return (string)$nextNo;
    }



    /**
     * Langkah 1: Validasi form, lalu tampilkan modal preview.
     * Data BELUM disimpan ke database di sini.
     */
    public function previewSubmit(): void
    {
        $this->validate([
            'items.*.item_category_id' => 'required',
            'items.*.type' => 'required|string',
            'items.*.size' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit' => 'required|string',
            'items.*.remaining' => 'required|numeric',
        ], [
            'items.*.item_category_id.required' => 'Kategori wajib dipilih',
            'items.*.type.required' => 'Nama barang wajib diisi',
            'items.*.size.required' => 'Ukuran wajib diisi',
            'items.*.quantity.required' => 'Jumlah wajib diisi',
            'items.*.quantity.min' => 'Minimal 1',
            'items.*.unit.required' => 'Satuan wajib diisi',
            'items.*.remaining.required' => 'Sisa wajib diisi',
        ]);

        // Urutkan items berdasarkan item_category_id agar grouping di UI benar
        $itemsCollection = collect($this->items);
        $this->items = $itemsCollection->sortBy('item_category_id')->values()->toArray();

        // Validasi lulus — tampilkan modal preview
        $this->showPreviewModal = true;
    }

    public function closePreview(): void
    {
        $this->showPreviewModal = false;
    }

    /**
     * Langkah 2: Konfirmasi dari modal preview, simpan ke database.
     */
    public function confirmSubmit(): void
    {
        $user = auth()->user();
        $details = $user?->detailsUser;

        // 1. Validasi Workflow Aktif
        $workflow = ApprovalWorkflow::where('status', 'active')->first();
        if (!$workflow) {
            $this->showPreviewModal = false;
            Log::error("PR Submission Failed: No active approval workflow found for User ID: " . ($user?->id ?? 'Unknown'));
            Notification::make()
                ->danger()
                ->color('danger')
                ->title('Error Workflow')
                ->body('Alur persetujuan (approval workflow) aktif tidak ditemukan. Silakan hubungi admin.')
                ->send();
            return;
        }

        // 2. Identifikasi Step Pertama (Requester) Secara Dinamis
        $currentStep = $workflow->steps()->orderBy('step_order', 'asc')->first();
        if (!$currentStep || !$user->roles()->where('roles.id', $currentStep->role_id)->exists()) {
            $this->showPreviewModal = false;
            Notification::make()
                ->danger()
                ->color('danger')
                ->title('Akses Ditolak')
                ->body('Role Anda tidak memiliki otoritas untuk melakukan pengajuan pada alur ini.')
                ->send();
            return;
        }

        // 3. Tentukan Step Berikutnya Secara Dinamis
        $nextStep = $workflow->steps()
            ->where('step_order', '>', $currentStep->step_order)
            ->orderBy('step_order', 'asc')
            ->first();

        // 4. Generate PR Number Unik 8 Digit
        do {
            $prNumber = (string) mt_rand(10000000, 99999999);
        } while (PrHeader::where('pr_number', $prNumber)->exists());

        $batchId = (string) Str::uuid();

        try {
            DB::transaction(function () use ($user, $details, $workflow, $currentStep, $nextStep, $prNumber, $batchId) {
                // A. Simpan PrHeader
                $header = PrHeader::create([
                    'pr_number' => $prNumber,
                    'pr_status' => PrStatusConstant::WAITING_APPROVAL,
                    'requester_id' => $user?->id,
                    'department_id' => $details?->department_id ?? Department::firstOrCreate(['name' => 'Departemen Kru Kapal'])->id,
                    'approval_workflow_id' => $workflow->id,
                    'current_role_id' => $nextStep?->role_id,
                    'current_step_id' => $nextStep?->id,
                    'description' => null,
                ]);

                // B. Simpan PrDetail
                $detail = PrDetail::create([
                    'pr_header_id' => $header->id,
                    'priority' => null,
                    'document_no' => $this->documentNo,
                    'title' => DocumentConstant::DOCUMENT_TITLE,
                    'issue_date' => app(DateService::class)->getIssueDate(),
                    'rev_no' => '00',
                    'ref_date' => null,
                    'document_type' => null,
                    'no' => $this->sequenceNo,
                    'needs' => $this->needs,
                    'vessel_id' => $user?->vessel_id,
                    'request_date' => app(DateService::class)->getCurrentDate(),
                    'request_date_client' => $this->clientDateTime,
                    'required_date' => null,
                    'expired_date' => null,
                    'description' => null,
                ]);

                // C. Simpan Snapshot ke PrLog
                PrLog::create([
                    'batch_id' => $batchId,
                    'action' => 'SUBMIT',
                    'status' => 'SUCCESS',
                    'notes' => 'Initial PR Submission',
                    'user_id' => $user?->id,
                    'role_id' => $currentStep->role_id,
                    'pr_header_id' => $header->id,
                    'pr_number' => $header->pr_number,
                    'pr_status' => $header->pr_status,
                    'requester_id' => $header->requester_id,
                    'department_id' => $header->department_id,
                    'approval_workflow_id' => $header->approval_workflow_id,
                    'current_role_id' => $header->current_role_id,
                    'current_step_id' => $header->current_step_id,
                    'header_description' => $header->description,
                    'priority' => $detail->priority,
                    'document_no' => $detail->document_no,
                    'title' => $detail->title,
                    'issue_date' => $detail->issue_date,
                    'rev_no' => $detail->rev_no,
                    'ref_date' => $detail->ref_date,
                    'document_type' => $detail->document_type,
                    'no' => $detail->no,
                    'needs' => $detail->needs,
                    'vessel_id' => $detail->vessel_id,
                    'request_date' => $detail->request_date,
                    'required_date' => $detail->required_date,
                    'expired_date' => $detail->expired_date,
                    'detail_description' => $detail->description,
                ]);

                // D. Simpan Snapshot ke PrHistory
                PrHistory::create([
                    'batch_id' => $batchId,
                    'pr_header_id' => $header->id,
                    'pr_number' => $header->pr_number,
                    'pr_status' => $header->pr_status,
                    'requester_id' => $header->requester_id,
                    'department_id' => $header->department_id,
                    'approval_workflow_id' => $header->approval_workflow_id,
                    'current_role_id' => $header->current_role_id,
                    'current_step_id' => $header->current_step_id,
                    'header_description' => $header->description,
                    'priority' => $detail->priority,
                    'document_no' => $detail->document_no,
                    'title' => $detail->title,
                    'issue_date' => $detail->issue_date,
                    'rev_no' => $detail->rev_no,
                    'ref_date' => $detail->ref_date,
                    'document_type' => $detail->document_type,
                    'no' => $detail->no,
                    'needs' => $detail->needs,
                    'vessel_id' => $detail->vessel_id,
                    'request_date' => $detail->request_date,
                    'required_date' => $detail->required_date,
                    'expired_date' => $detail->expired_date,
                    'detail_description' => $detail->description,
                ]);

                // E. Simpan Items
                foreach ($this->items as $itemData) {
                    Item::create([
                        'pr_detail_id' => $detail->id,
                        'vessel_id' => $user->vessel_id,
                        'item_category_id' => $itemData['item_category_id'],
                        'type' => $itemData['type'],
                        'size' => $itemData['size'],
                        'quantity' => $itemData['quantity'],
                        'unit' => $itemData['unit'],
                        'remaining' => $itemData['remaining'],
                        'description' => $itemData['type'],
                    ]);
                }
            });

            Notification::make()
                ->success()
                ->color('success')
                ->title('PR Berhasil Dikirim')
                ->body("Pengajuan PR dengan nomor {$prNumber} telah berhasil dikirim dan menunggu persetujuan.")
                ->send();

            $this->reset(['items', 'needs']);
            $this->showPreviewModal = false;
            $this->mount(); // Refresh document number and sequence

        } catch (\Exception $e) {
            $this->showPreviewModal = false;
            Log::error("PR Submission Error: " . $e->getMessage());
            Notification::make()
                ->danger()
                ->color('danger')
                ->title('Gagal Menyimpan PR')
                ->body('Terjadi kesalahan saat menyimpan data. Silakan coba lagi.')
                ->send();
        }
    }
}
