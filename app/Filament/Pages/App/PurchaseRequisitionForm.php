<?php

namespace App\Filament\Pages\App;

use App\Constants\DocumentConstant;
use App\Constants\PrStatusConstant;
use App\Models\PrHeader;
use App\Models\PrDetail;
use App\Models\Item;
use App\Models\Department;
use App\Models\ItemCategory;
use App\Models\PrLog;
use App\Models\PrHistory;
use App\Models\ItemLog;
use App\Models\ItemHistory;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use App\Service\ApprovalFlowService;
use App\Service\DateService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use UnitEnum;

class PurchaseRequisitionForm extends Page
{

    protected static ?string $navigationLabel = 'Form Pengajuan Barang';
    protected static ?string $title = 'Formulir Permintaan';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-pencil-square';
    protected static ?int $navigationSort = 1;
    protected string $view = 'filament.app.pages.purchase-requisition-form';

    public static function canAccess(): bool
    {
        return auth()->user()?->roles()->where('name', \App\Constants\RoleConstant::VESSEL_CREW_REQUESTER)->exists() ?? false;
    }

    public ?array $data = [];
    public string $sequenceNo = '';
    public string $departmentName = '';
    public string $vesselName = '';
    public string $companyCode = '';
    public string $documentNo = '';
    public string $clientDateTime = '';
    public string $needs = 'Mesin';
    public string $deliveryAddress = '';

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
            ['item_category_id' => '', 'type' => '', 'size' => '', 'quantity' => '', 'unit' => '', 'remaining' => '', 'item_priority' => '']
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
            'item_priority' => '',
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
        try {
            $this->validate([
                'deliveryAddress' => 'required|string',
                'items.*.item_category_id' => 'required',
                'items.*.type' => 'required|string',
                'items.*.size' => 'required|string',
                'items.*.quantity' => 'required|numeric|min:1',
                'items.*.unit' => 'required|string',
                'items.*.remaining' => 'required|numeric',
                'items.*.item_priority' => 'required|string',
            ], [
                'items.*.item_category_id.required' => 'Kategori wajib dipilih',
                'items.*.type.required' => 'Nama barang wajib diisi',
                'items.*.size.required' => 'Ukuran wajib diisi',
                'items.*.quantity.required' => 'Jumlah wajib diisi',
                'items.*.quantity.min' => 'Minimal 1',
                'items.*.unit.required' => 'Satuan wajib diisi',
                'items.*.remaining.required' => 'Sisa wajib diisi',
                'items.*.item_priority.required' => 'Prioritas wajib dipilih',
                'deliveryAddress.required' => 'Tujuan pengiriman wajib diisi',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('validation-failed');
            throw $e;
        }

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
     * Real-time Validation
     * Dijalankan setiap kali field di dalam array $items berubah.
     */
    public function updatedItems($value, $name): void
    {
        try {
            // $name akan berisi format seperti 'items.0.type'
            $this->validateOnly($name, [
                'items.*.item_category_id' => 'required',
                'items.*.type' => 'required|string',
                'items.*.size' => 'required|string',
                'items.*.quantity' => 'required|numeric|min:1',
                'items.*.unit' => 'required|string',
                'items.*.remaining' => 'required|numeric',
                'items.*.item_priority' => 'required|string',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('validation-failed');
            throw $e;
        }
    }

    /**
     * Langkah 2: Konfirmasi dari modal preview, simpan ke database.
     */
    public function confirmSubmit($lat = null, $lng = null): void
    {
        // Simpan ke session jika dikirim dari client untuk cadangan
        if ($lat && $lng) {
            session([
                'user_latitude' => $lat,
                'user_longitude' => $lng
            ]);
        }

        $user = auth()->user();
        $details = $user?->detailsUser;

        // 1-3. Validasi alur approval via service terpusat
        $flowContext = app(ApprovalFlowService::class)->resolveSubmissionContext($user);
        if (! $flowContext['ok']) {
            $this->showPreviewModal = false;
            Log::warning('PR Submission Blocked: ' . $flowContext['message'] . ' | User ID: ' . ($user?->id ?? 'Unknown'));
            Notification::make()
                ->danger()
                ->color('danger')
                ->title($flowContext['title'])
                ->body($flowContext['message'])
                ->send();
            return;
        }

        $workflow    = $flowContext['workflow'];
        $currentStep = $flowContext['currentStep'];

        // Tentukan step berikutnya — PR langsung di-assign ke step 2 agar approver bisa melihatnya
        $nextStep = $workflow->steps()
            ->where('step_order', '>', $currentStep->step_order)
            ->orderBy('step_order', 'asc')
            ->first();

        if (! $nextStep) {
            $this->showPreviewModal = false;
            Notification::make()
                ->danger()
                ->color('danger')
                ->title('Step berikutnya tidak ditemukan')
                ->body('Step approval berikutnya tidak ditemukan di workflow aktif. Silakan hubungi admin.')
                ->send();
            return;
        }

        // 4. Generate PR Number Unik 8 Digit
        do {
            $prNumber = (string) mt_rand(10000000, 99999999);
        } while (PrHeader::where('pr_number', $prNumber)->exists());

        $batchId = (string) Str::uuid();
        $newHeaderId = null;

        try {
            DB::transaction(function () use ($user, $details, $workflow, $currentStep, $nextStep, $prNumber, $batchId, &$newHeaderId) {
                $requestedItemsPayload = [
                    'items' => collect($this->items)->values()->map(function ($item) {
                        return [
                            'item_category_id' => $item['item_category_id'] ?? null,
                            'type' => $item['type'] ?? null,
                            'size' => $item['size'] ?? null,
                            'quantity' => isset($item['quantity']) ? (float) $item['quantity'] : null,
                            'unit' => $item['unit'] ?? null,
                            'remaining' => isset($item['remaining']) ? (float) $item['remaining'] : null,
                            'item_priority' => $item['item_priority'] ?? null,
                        ];
                    })->toArray(),
                ];

                // A. Simpan PrHeader — langsung di-assign ke nextStep (step 2) agar approver bisa lihat
                $header = PrHeader::create([
                    'batch_id' => $batchId,
                    'pr_number' => $prNumber,
                    'pr_status' => PrStatusConstant::WAITING_APPROVAL,
                    'requester_id' => $user?->id,
                    'department_id' => $details?->department_id ?? Department::firstOrCreate(['name' => 'Departemen Kru Kapal'])->id,
                    'approval_workflow_id' => $workflow->id,
                    'current_role_id' => $nextStep->role_id,
                    'current_step_id' => $nextStep->id,
                    'description' => null,
                ]);
                $newHeaderId = $header->id;

                // B. Simpan PrDetail
                $dateService = app(DateService::class);
                $detail = PrDetail::create([
                    'pr_header_id' => $header->id,
                    'priority' => null,
                    'document_no' => $this->documentNo,
                    'title' => DocumentConstant::DOCUMENT_TITLE,
                    'issue_date' => $dateService->getCurrentDate(), // Sekarang disimpan sebagai Carbon/DateTime
                    'rev_no' => '00',
                    'ref_date' => null,
                    'document_type' => null,
                    'no' => $this->sequenceNo,
                    'needs' => $this->needs,
                    'vessel_id' => $user?->vessel_id,
                    'request_date' => $dateService->getCurrentDate(),
                    'request_date_client' => $dateService->parseLocalizedDate($this->clientDateTime), // Parsing string Indonesia ke DateTime
                    'required_date' => null,
                    'expired_date' => null,
                    'latitude' => session('user_latitude'),
                    'longitude' => session('user_longitude'),
                    'delivery_address' => $this->deliveryAddress,
                    'description' => null,
                ]);

                // C. Simpan Snapshot ke PrLog
                PrLog::create([
                    'batch_id' => $batchId,
                    'action' => 'SUBMIT',
                    'status' => 'SUCCESS',
                    'notes' => 'Initial PR Submission',
                    'payload' => $requestedItemsPayload,
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
                    'latitude' => $detail->latitude,
                    'longitude' => $detail->longitude,
                    'delivery_address' => $detail->delivery_address,
                    'detail_description' => $detail->description,
                ]);

                // D. Simpan Snapshot ke PrHistory
                PrHistory::create([
                    'batch_id' => $batchId,
                    'payload' => $requestedItemsPayload,
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
                    'latitude' => $detail->latitude,
                    'longitude' => $detail->longitude,
                    'delivery_address' => $detail->delivery_address,
                    'detail_description' => $detail->description,
                ]);

                // E. Simpan Items & Snapshots
                foreach ($this->items as $itemData) {
                    $item = Item::create([
                        'pr_detail_id' => $detail->id,
                        'vessel_id' => $user->vessel_id,
                        'item_category_id' => $itemData['item_category_id'],
                        'type' => $itemData['type'],
                        'size' => $itemData['size'],
                        'quantity' => $itemData['quantity'],
                        'unit' => $itemData['unit'],
                        'remaining' => $itemData['remaining'],
                        'item_priority' => $itemData['item_priority'],
                        'status' => PrStatusConstant::WAITING_APPROVAL,
                        // 'description' => $itemData['type'],
                    ]);

                    // Snapshot ke items_log
                    ItemLog::create([
                        'batch_id' => $batchId,
                        'pr_detail_id' => $detail->id,
                        'vessel_id' => $user->vessel_id,
                        'item_category_id' => $itemData['item_category_id'],
                        'no' => $item->no,
                        'type' => $itemData['type'],
                        'size' => $itemData['size'],
                        'description' => $itemData['type'],
                        'quantity' => $itemData['quantity'],
                        'unit' => $itemData['unit'],
                        'remaining' => $itemData['remaining'],
                        'item_priority' => $itemData['item_priority'],
                        'status' => PrStatusConstant::WAITING_APPROVAL,
                        'step_order' => $currentStep->step_order,
                    ]);

                    // Snapshot ke items_history
                    ItemHistory::create([
                        'batch_id' => $batchId,
                        'pr_detail_id' => $detail->id,
                        'vessel_id' => $user->vessel_id,
                        'item_category_id' => $itemData['item_category_id'],
                        'no' => $item->no,
                        'type' => $itemData['type'],
                        'size' => $itemData['size'],
                        'description' => $itemData['type'],
                        'quantity' => $itemData['quantity'],
                        'unit' => $itemData['unit'],
                        'remaining' => $itemData['remaining'],
                        'item_priority' => $itemData['item_priority'],
                        'status' => PrStatusConstant::WAITING_APPROVAL,
                        'step_order' => $currentStep->step_order,
                    ]);
                }
            });

            Notification::make()
                ->success()
                ->color('success')
                ->title('PR Berhasil Dikirim')
                ->body("Pengajuan PR dengan nomor {$prNumber} telah berhasil dikirim dan menunggu persetujuan.")
                ->send();

            $this->showPreviewModal = false;
            $this->redirect(
                route('filament.app.pages.purchase-requisition-request-list') . '?openPr=' . $newHeaderId
            );

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
