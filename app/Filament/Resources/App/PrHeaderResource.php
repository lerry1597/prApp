<?php

namespace App\Filament\Resources\App;

use App\Constants\PrStatusConstant;
use App\Filament\Resources\App\PrHeaderResource\Pages\ManagePrHeaders;
use App\Models\Item;
use App\Models\ItemHistory;
use App\Models\ItemLog;
use App\Models\PrHeader;
use App\Models\PrHistory;
use App\Models\PrLog;
use App\Service\ApprovalFlowService;
use BackedEnum;
use UnitEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Livewire;
use Filament\Infolists\Components\TextEntry;

class PrHeaderResource extends Resource
{
    protected static ?string $model = PrHeader::class;

    protected static ?string $slug = 'approvals';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;


    protected static ?string $navigationLabel = 'Daftar pengajuan barang';
    protected static ?int $navigationSort = 2;
    protected static ?string $pluralModelLabel = 'Daftar pengajuan barang';
    protected static ?string $modelLabel = 'Daftar pengajuan barang';

    // public static function canViewAny(): bool
    // {
    //     /** @var \App\Models\User $user */
    //     $user = auth()->user();

    //     // Izinkan jika user memiliki role selain 'vessel_crew_requester'
    //     return $user && $user->roles()
    //         ->where('name', '!=', \App\Constants\RoleConstant::VESSEL_CREW_REQUESTER)
    //         ->exists();
    // }

    public static function canAccess(): bool
    {
        return auth()->user()?->roles()->where('name', \App\Constants\RoleConstant::TECHNICAL_APPROVER)->exists() ?? false;
    }


    public static function getEloquentQuery(): Builder
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $userRoleIds = $user->roles()->pluck('roles.id')->toArray();

        return parent::getEloquentQuery()
            ->visibleToUser(auth()->user());
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                // ── Ringkasan Status (selalu terlihat) ──
                Section::make()
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('pr_number')
                                    ->label('Nomor PR')
                                    ->weight('bold')
                                    ->size('lg')
                                    ->color('primary')
                                    ->icon('heroicon-m-hashtag')
                                    ->copyable(),

                                TextEntry::make('pr_status')
                                    ->label('Status Pengajuan')
                                    ->badge()
                                    ->formatStateUsing(fn(string $state): string => PrStatusConstant::getStatuses()[$state] ?? $state)
                                    ->color(fn(string $state): string => PrStatusConstant::getColor($state)),

                                TextEntry::make('currentStep.name')
                                    ->label('Step Approval Aktif')
                                    ->badge()
                                    ->color('info')
                                    ->icon('heroicon-m-arrow-right-circle')
                                    ->placeholder('Selesai'),
                            ]),
                    ]),

                // ── Detail Lengkap Pengajuan ──
                Section::make('Informasi Pengajuan')
                    ->icon('heroicon-o-information-circle')
                    ->description('Rincian lengkap dari purchase requisition ini')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('requester.name')
                                    ->label('Pemohon')
                                    ->icon('heroicon-m-user-circle')
                                    ->weight('medium'),

                                TextEntry::make('department.name')
                                    ->label('Departemen')
                                    ->icon('heroicon-m-building-office-2')
                                    ->placeholder('—'),

                                TextEntry::make('detail.vessel.name')
                                    ->label('Nama Kapal')
                                    ->icon('lucide-ship')
                                    ->weight('medium'),

                                TextEntry::make('detail.needs')
                                    ->label('Keperluan')
                                    ->icon('heroicon-m-wrench-screwdriver')
                                    ->placeholder('—'),

                                TextEntry::make('detail.request_date')
                                    ->label('Tgl Pengajuan')
                                    ->date('d M Y, H:i')
                                    ->icon('heroicon-m-calendar-days'),

                                TextEntry::make('detail.required_date')
                                    ->label('Tgl Dibutuhkan')
                                    ->date('d M Y')
                                    ->icon('heroicon-m-calendar')
                                    ->color('warning')
                                    ->placeholder('—'),
                            ]),

                        Grid::make(1)
                            ->schema([
                                TextEntry::make('description')
                                    ->label('Keterangan')
                                    ->icon('heroicon-m-chat-bubble-left-ellipsis')
                                    ->placeholder('Tidak ada keterangan'),
                            ]),
                    ])
                    ->collapsible(),

                // ── Daftar Barang ──
                Livewire::make(\App\Livewire\PrItemsTable::class)
                    ->key(fn($record) => 'pr-items-' . ($record?->id ?? 'new')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('pr_number')
                    ->label('No. PR')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('primary')
                    ->copyable()
                    ->icon('heroicon-m-hashtag'),

                \Filament\Tables\Columns\TextColumn::make('requester.name')
                    ->label('Pemohon')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-m-user')
                    ->description(fn(PrHeader $record): ?string => $record->department?->name),

                \Filament\Tables\Columns\TextColumn::make('detail.vessel.name')
                    ->label('Kapal')
                    ->searchable()
                    ->icon('lucide-ship')
                    ->description(fn(PrHeader $record): ?string => $record->detail?->needs),

                \Filament\Tables\Columns\TextColumn::make('detail.request_date')
                    ->label('Tgl Pengajuan')
                    ->date('d M Y')
                    ->sortable()
                    ->icon('heroicon-m-calendar-days')
                    ->description(
                        fn(PrHeader $record): ?string => $record->detail?->required_date
                            ? 'Dibutuhkan: ' . $record->detail->required_date->format('d M Y')
                            : null
                    ),

                \Filament\Tables\Columns\TextColumn::make('pr_status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => PrStatusConstant::getStatuses()[$state] ?? $state)
                    ->color(fn(string $state): string => PrStatusConstant::getColor($state)),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->emptyStateHeading('Tidak ada pengajuan')
            ->emptyStateDescription('Belum ada PR yang perlu disetujui saat ini.')
            ->emptyStateIcon('heroicon-o-clipboard-document-check')
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('pr_status')
                    ->label('Filter Status')
                    ->options(\App\Constants\PrStatusConstant::getStatuses()),
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make()
                    ->label('Proses Pengajuan')
                    ->color('warning')
                    ->button()
                    ->modalHeading('Proses Pengajuan PR')
                    ->modalWidth('7xl')
                    ->modalSubmitAction(function (\Filament\Actions\Action $action): \Filament\Actions\Action {
                        return $action
                            ->label('Setujui Pengajuan')
                            ->color('success')
                            ->icon('heroicon-o-check-badge');
                    })
                    ->action(fn(PrHeader $record) => static::handleApproval($record))
                    ->modalCancelActionLabel('Batal')
                    ->icon('heroicon-o-eye'),
            ]);
    }

    /**
     * Menangani proses approval untuk satu PR Header.
     *
     * Alur:
     *  1. Validasi data dasar (user, header, detail PR).
     *  2. Cari approval workflow yang aktif untuk PR ini.
     *  3. Pastikan current step ada di workflow tersebut.
     *  4. Pastikan role user sesuai dengan current step (otorisasi).
     *  5. Cari next step berdasarkan step_order yang lebih besar dari current step.
     *  6. Validasi semua item: quantity harus numeric dan >= 1.
     *  7. Jalankan DB transaction:
     *     a. Update pr_headers: status, current_step, current_role, approver, approved_at.
     *     b. Buat snapshot audit ke pr_logs (lengkap + payload quantity).
     *     c. Buat snapshot riwayat ke pr_histories.
     *     d. Buat snapshot setiap item ke items_log dan items_history.
     *  8. Tampilkan notifikasi sukses atau error jika exception.
     */
    public static function handleApproval(PrHeader $record): void
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        // Ambil data terbaru dari DB beserta relasi yang dibutuhkan
        $header = $record->fresh(['detail', 'currentStep', 'approvalWorkflow']);

        // --- 1. Validasi data dasar ---
        if (! $user || ! $header || ! $header->detail) {
            Notification::make()->danger()->title('Data tidak lengkap')->body('Data PR tidak ditemukan atau belum memiliki detail.')->send();
            return;
        }

        // --- 2-5. Validasi alur approval via service terpusat ---
        $flowContext = app(ApprovalFlowService::class)->resolveApprovalContext($user, $header);
        if (! $flowContext['ok']) {
            Notification::make()->danger()->title($flowContext['title'])->body($flowContext['message'])->send();
            return;
        }

        $currentStep = $flowContext['currentStep'];
        $nextStep = $flowContext['nextStep'];

        // Ambil semua item PR (sudah tersimpan via TextInputColumn sebelum tombol diklik)
        $items = $header->items()->orderBy('id')->get();

        if ($items->isEmpty()) {
            Notification::make()->danger()->title('Item tidak ditemukan')->body('Tidak ada item yang dapat diproses untuk PR ini.')->send();
            return;
        }

        // --- 6. Validasi quantity semua item ---
        foreach ($items as $item) {
            if (! is_numeric($item->quantity) || $item->quantity < 1) {
                Notification::make()->danger()->title('Jumlah tidak valid')->body("Jumlah untuk item {$item->type} harus berupa angka dan minimal 1.")->send();
                return;
            }
        }

        $now = now();
        $batchId = $header->batch_id;

        // --- 7. DB Transaction: semua perubahan atomik ---
        try {
            DB::transaction(function () use ($batchId, $currentStep, $header, $nextStep, $now, $user): void {
                // 7a. Update pr_headers ke step berikutnya
                // Jika nextStep null = step terakhir = PR selesai disetujui
                $isFinalStep = $nextStep === null;

                DB::table('pr_headers')
                    ->where('id', $header->id)
                    ->update([
                        'pr_status'       => \App\Constants\PrStatusConstant::APPROVED,
                        'current_role_id' => $isFinalStep ? null : $nextStep->role_id,
                        'current_step_id' => $isFinalStep ? null : $nextStep->id,
                        'approver_id'     => $user->id,
                        'approved_at'     => $now,
                        'updated_at'      => $now,
                    ]);

                // Refresh agar data header & items yang dipakai di bawah sudah up-to-date
                $header->refresh()->loadMissing('detail');
                $detail = $header->detail;
                $latestItems = $header->items()->orderBy('id')->get();

                // 7b. Buat audit log lengkap ke pr_logs (termasuk payload quantity)
                PrLog::create([
                    'batch_id' => $batchId,
                    'action' => 'APPROVE',
                    'status' => 'SUCCESS',
                    'notes' => 'Approver updated item quantities and submitted approval.',
                    'user_id' => $user->id,
                    'role_id' => $currentStep->role_id,
                    'pr_header_id' => $header->id,
                    'pr_number' => $header->pr_number,
                    'pr_status' => $header->pr_status,
                    'requester_id' => $header->requester_id,
                    'department_id' => $header->department_id,
                    'approver_id' => $header->approver_id,
                    'approval_workflow_id' => $header->approval_workflow_id,
                    'current_role_id' => $header->current_role_id,
                    'current_step_id' => $header->current_step_id,
                    'header_description' => $header->description,
                    'priority' => $detail?->priority,
                    'document_no' => $detail?->document_no,
                    'title' => $detail?->title,
                    'issue_date' => $detail?->issue_date,
                    'rev_no' => $detail?->rev_no,
                    'ref_date' => $detail?->ref_date,
                    'document_type' => $detail?->document_type,
                    'no' => $detail?->no,
                    'needs' => $detail?->needs,
                    'vessel_id' => $detail?->vessel_id,
                    'request_date' => $detail?->request_date,
                    'required_date' => $detail?->required_date,
                    'expired_date' => $detail?->expired_date,
                    'latitude' => $detail?->latitude,
                    'longitude' => $detail?->longitude,
                    'delivery_address' => $detail?->delivery_address,
                    'detail_description' => $detail?->description,
                    'payload' => [
                        'items' => $latestItems->values()->map(fn(Item $item): array => [
                            'id'               => $item->id,
                            'item_category_id' => $item->item_category_id,
                            'type'             => $item->type,
                            'size'             => $item->size,
                            'quantity'         => (float) $item->quantity,
                            'unit'             => $item->unit,
                            'remaining'        => (float) $item->remaining,
                            'item_priority'    => $item->item_priority,
                        ])->all(),
                        'next_step_id' => $header->current_step_id,
                        'next_role_id' => $header->current_role_id,
                    ],
                ]);

                // 7c. Buat riwayat header ke pr_histories (snapshot status header)
                PrHistory::create([
                    'batch_id' => $batchId,
                    'payload' => [
                        'items' => $latestItems->values()->map(fn(Item $item): array => [
                            'id'               => $item->id,
                            'item_category_id' => $item->item_category_id,
                            'type'             => $item->type,
                            'size'             => $item->size,
                            'quantity'         => (float) $item->quantity,
                            'unit'             => $item->unit,
                            'remaining'        => (float) $item->remaining,
                            'item_priority'    => $item->item_priority,
                        ])->all(),
                    ],
                    'pr_header_id' => $header->id,
                    'pr_number' => $header->pr_number,
                    'pr_status' => $header->pr_status,
                    'requester_id' => $header->requester_id,
                    'department_id' => $header->department_id,
                    'approver_id' => $header->approver_id,
                    'approval_workflow_id' => $header->approval_workflow_id,
                    'current_role_id' => $header->current_role_id,
                    'current_step_id' => $header->current_step_id,
                    'header_description' => $header->description,
                    'priority' => $detail?->priority,
                    'document_no' => $detail?->document_no,
                    'title' => $detail?->title,
                    'issue_date' => $detail?->issue_date,
                    'rev_no' => $detail?->rev_no,
                    'ref_date' => $detail?->ref_date,
                    'document_type' => $detail?->document_type,
                    'no' => $detail?->no,
                    'needs' => $detail?->needs,
                    'vessel_id' => $detail?->vessel_id,
                    'request_date' => $detail?->request_date,
                    'required_date' => $detail?->required_date,
                    'expired_date' => $detail?->expired_date,
                    'latitude' => $detail?->latitude,
                    'longitude' => $detail?->longitude,
                    'delivery_address' => $detail?->delivery_address,
                    'detail_description' => $detail?->description,
                ]);

                // 7d. Buat snapshot setiap item ke items_log dan items_history
                foreach ($latestItems as $item) {
                    $itemSnapshot = [
                        'batch_id' => $batchId,
                        'pr_detail_id' => $item->pr_detail_id,
                        'vessel_id' => $item->vessel_id,
                        'item_category_id' => $item->item_category_id,
                        'no' => $item->no,
                        'type' => $item->type,
                        'size' => $item->size,
                        'description' => $item->description,
                        'quantity' => $item->quantity,
                        'unit' => $item->unit,
                        'remaining' => $item->remaining,
                        'item_priority' => $item->item_priority,
                        'step_order' => $currentStep->step_order,
                    ];
                    ItemLog::create($itemSnapshot);
                    ItemHistory::create($itemSnapshot);
                }
            });
        } catch (\Throwable $e) {
            // --- 8. Tampilkan error jika transaction gagal ---
            Notification::make()->danger()->title('Gagal menyimpan approval')->body($e->getMessage())->send();
            return;
        }

        // --- 8. Notifikasi sukses ---
        Notification::make()
            ->success()
            ->title('Approval berhasil disimpan')
            ->body('PR berhasil diproses ke step berikutnya.')
            ->send();
    }

    public static function getPages(): array
    {
        return [
            'index' => ManagePrHeaders::route('/'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
