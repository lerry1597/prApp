<?php

namespace App\Filament\Pages\App;

use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use App\Constants\DocumentConstant;
use App\Models\PrHeader;
use App\Models\PrDetail;
use App\Models\Item;
use App\Models\Department;
use App\Models\ItemCategory;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Filament\Support\Enums\FontWeight;

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
    }

    protected function generateSequenceNo(): string
    {
        $latestNo = PrDetail::max('no');
        $nextNo = $latestNo ? (int)$latestNo + 1 : 1;
        return str_pad($nextNo, 4, '0', STR_PAD_LEFT);
    }

    protected function fillForm(): void
    {
        // $this->getSchema('form')->fill([]);
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
                            ->state(DocumentConstant::ISSUE_DATE)
                            ->weight(FontWeight::Medium)
                            ->color('gray'),
                        TextEntry::make('rev_no')
                            ->label('No. Revisi')
                            ->state(DocumentConstant::REV_NO)
                            ->weight(FontWeight::Medium)
                            ->color('gray'),
                        TextEntry::make('ref_date')
                            ->label('Tanggal Referensi')
                            ->state(DocumentConstant::REF_DATE)
                            ->weight(FontWeight::Medium)
                            ->color('gray'),
                        TextEntry::make('department_name')
                            ->label('Departemen')
                            ->state(fn() => $this->departmentName)
                            ->weight(FontWeight::Medium)
                            ->color('gray'),
                        TextEntry::make('no')
                            ->label('No. Urut')
                            ->state(fn() => $this->sequenceNo)
                            ->weight(FontWeight::Medium)
                            ->color('gray'),
                        Radio::make('needs')
                            ->label('Kebutuhan')
                            ->options([
                                'Mesin' => 'Mesin',
                                'Dek' => 'Dek',
                            ])
                            ->required()
                            ->inline(),
                        DatePicker::make('required_date')
                            ->placeholder('Pilih Tanggal')
                            ->label('Tanggal Dibutuhkan')
                            ->maxWidth('full')
                            ->native(false)
                            ->default(null),
                    ])
            ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Daftar Item')
                    ->schema([
                        Repeater::make('items')
                            ->schema([
                                Select::make('item_category_id')
                                    ->label('Kategori Item')
                                    ->placeholder('Pilih Kategori')
                                    ->options(ItemCategory::pluck('name', 'id'))
                                    ->searchable()
                                    ->required(),
                                // TextInput::make('type')
                                //     ->label('Tipe'),
                                // TextInput::make('size')
                                //     ->label('Ukuran'),
                                // TextInput::make('quantity')
                                //     ->label('Jumlah')
                                //     ->numeric()
                                //     ->required(),
                                // TextInput::make('unit')
                                //     ->label('Satuan')
                                //     ->placeholder('Pcs, Box, dll')
                                //     ->required(),
                                MarkdownEditor::make('description')
                                    // ->label('Harap belikan barang sebagai berikut : ')
                                    ->hiddenLabel()
                                    ->default('Harap belikan barang sebagai berikut : ')
                                    ->toolbarButtons([
                                        'bold',
                                        'italic',
                                        'strike',
                                        'link',
                                        'heading',
                                        'bulletList',
                                        'orderedList',
                                        'table',
                                        'undo',
                                        'redo',
                                    ])
                                    ->required()
                                    ->autofocus()
                                    ->columnSpanFull(),
                            ])
                            ->columns(5)
                            ->defaultItems(1)
                            ->required()
                            ->minItems(1)
                            ->addActionLabel('Tambah')
                            ->addAction(fn($action) => $action->icon('heroicon-m-plus')),
                    ]),
            ]);
    }

    public function submit(): void
    {
        $formData = $this->getSchema('form')->getState();
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
            'document_no' => $formDocument['document_no'],
            'title' => $formDocument['title'],
            'issue_date' => $formDocument['issue_date'],
            'rev_no' => $formDocument['rev_no'],
            'ref_date' => $formDocument['ref_date'],
            'document_type' => null,
            'no' => $this->sequenceNo,
            'needs' => $formDocument['needs'],
            'vessel_id' => $user?->vessel_id,
            'request_date' => now(),
            'required_date' => $formDocument['required_date'] ?? null,
            'expired_date' => null,
            'description' => null,
        ]);

        // 3. Simpan Items
        foreach ($formData['items'] as $itemData) {
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

        // Reset form and update sequence
        $this->sequenceNo = $this->generateSequenceNo();
        $this->fillForm();
    }
}
