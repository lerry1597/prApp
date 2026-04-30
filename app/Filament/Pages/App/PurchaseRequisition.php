<?php

namespace App\Filament\Pages\App;

use App\Constants\DocumentConstant;
use App\Models\PrHeader;
use App\Models\PrDetail;
use App\Models\Item;
use App\Models\Department;
use App\Models\ItemCategory;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use App\Service\DateService;

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

        // 1. Simpan PrHeader
        $header = PrHeader::create([
            'pr_number' => $this->documentNo,
            'pr_status' => 'pending',
            'requester_id' => $user?->id,
            'department_id' => $details?->department_id ?? Department::firstOrCreate(['name' => 'Departemen Kru Kapal'])->id,
            'description' => null,
        ]);

        // 2. Simpan PrDetail
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

        // 3. Simpan Items
        foreach ($this->items as $itemData) {
            Item::create([
                'pr_detail_id' => $detail->id,
                'vessel_id' => $user?->vessel_id,
                'item_category_id' => $itemData['item_category_id'],
                'no' => $itemData['no'] ?? null,
                'type' => $itemData['type'] ?? null,
                'size' => $itemData['size'] ?? null,
                'description' => $itemData['description'] ?? null,
                'quantity' => $itemData['quantity'],
                'unit' => $itemData['unit'],
                'remaining' => $itemData['remaining'] ?? null,
            ]);
        }

        $this->showPreviewModal = false;

        Notification::make()
            ->title('Pengajuan PR Berhasil')
            ->body('Data pengajuan PR Anda telah berhasil disimpan.')
            ->success()
            ->send();

        $this->sequenceNo = $this->generateSequenceNo();
        $this->mount();
    }
}
