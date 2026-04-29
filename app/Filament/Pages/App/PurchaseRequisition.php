<?php

namespace App\Filament\Pages\App;

use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use App\Constants\DocumentConstant;
use App\Models\PrHeader;
use App\Models\PrDetail;
use App\Models\Item;
use App\Models\Department;
use App\Models\ItemCategory;
use Filament\Actions\Action as ActionsAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Actions\Action;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Support\Enums\FontWeight;
use App\Service\DateService;

class PurchaseRequisition extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected static ?string $navigationLabel = 'Form Pengajuan PR';
    protected static ?string $title = 'Formulir Permintaan';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-pencil-square';
    protected string $view = 'filament.app.pages.purchase-requisition';

    public ?array $data = [];
    public string $sequenceNo = '';
    public string $departmentName = '';
    
    public array $items = [];
    public array $itemCategories = [];

    public function getHeading(): string
    {
        return '';
    }

    public function mount(): void
    {
        $this->fillForm();
        $this->sequenceNo = $this->generateSequenceNo();

        $user = auth()->user();
        $details = $user?->detailsUser;
        $this->departmentName = $details?->department?->name ?? 'Kru Kapal';

        $this->itemCategories = ItemCategory::pluck('name', 'id')->toArray();
        $this->items = [
            ['item_category_id' => '', 'type' => '', 'size' => '', 'quantity' => '', 'unit' => '']
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
        ];
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
        $latestNo = PrDetail::max('no');
        $nextNo = $latestNo ? (int)$latestNo + 1 : 1;
        return str_pad($nextNo, 4, '0', STR_PAD_LEFT);
    }

    protected function fillForm(): void
    {
        $this->form->fill();
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columns([
                        'default' => 1,
                        'sm' => 3,
                    ])
                    ->schema([
                        TextEntry::make('form_title')
                            ->hiddenLabel()
                            ->state(DocumentConstant::DOCUMENT_TITLE)
                            ->columnSpanFull()
                            ->extraAttributes([
                                'style' => 'display: block !important; text-align: center !important; font-size: 1.75rem !important; font-weight: 800 !important; width: 100% !important; padding-top: 1rem !important; padding-bottom: 1rem !important;'
                            ]),
                        TextEntry::make('document_no')
                            ->label('No. Dokumen')
                            ->state(DocumentConstant::DOCUMENT_NO)
                            ->weight(FontWeight::Medium)
                            ->color('gray'),
                        TextEntry::make('issue_date')
                            ->label('Tanggal Terbit')
                            ->state(app(DateService::class)->getIssueDate())
                            ->weight(FontWeight::Medium)
                            ->color('gray'),
                        TextEntry::make('department_name')
                            ->label('Nama Kapal')
                            ->state("KN. GULAR")
                            ->weight(FontWeight::Medium)
                            ->color('gray'),
                        Radio::make('needs')
                            ->label('Kebutuhan')
                            ->options([
                                'Mesin' => 'Mesin',
                                'Dek' => 'Dek',
                            ])
                            ->required()
                            ->inline()
                    ])
            ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([]);
    }

    public function submit(): void
    {
        $this->validate([
            'items.*.item_category_id' => 'required',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit' => 'required|string',
        ]);

        $formDocument = $this->getSchema('infolist')->getState();
        $user = auth()->user();
        $details = $user?->detailsUser;

        // 1. Simpan PrHeader
        $header = PrHeader::create([
            'pr_number' => 'PR-' . strtoupper(uniqid()),
            'pr_status' => 'pending',
            'requester_id' => $user?->id,
            'department_id' => $details?->department_id ?? Department::firstOrCreate(['name' => 'Departemen Kru Kapal'])->id,
            'description' => null,
        ]);

        // 2. Simpan PrDetail
        $detail = PrDetail::create([
            'pr_header_id' => $header->id,
            'priority' => null,
            'document_no' => $formDocument['document_no'] ?? DocumentConstant::DOCUMENT_NO,
            'title' => $formDocument['title'] ?? DocumentConstant::DOCUMENT_TITLE,
            'issue_date' => $formDocument['issue_date'] ?? app(DateService::class)->getIssueDate(),
            'rev_no' => $formDocument['rev_no'] ?? '00',
            'ref_date' => $formDocument['ref_date'] ?? null,
            'document_type' => null,
            'no' => $this->sequenceNo,
            'needs' => $formDocument['needs'] ?? 'Mesin',
            'vessel_id' => $user?->vessel_id,
            'request_date' => app(DateService::class)->getCurrentDate(),
            'required_date' => $formDocument['required_date'] ?? null,
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
            ]);
        }

        Notification::make()
            ->title('Pengajuan PR Berhasil')
            ->body('Data pengajuan PR Anda telah berhasil disimpan.')
            ->success()
            ->send();

        $this->sequenceNo = $this->generateSequenceNo();
        $this->mount();
    }
}
