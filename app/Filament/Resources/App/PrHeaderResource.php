<?php

namespace App\Filament\Resources\App;

use App\Constants\PrStatusConstant;
use App\Filament\Resources\App\PrHeaderResource\Pages\ManagePrHeaders;
use App\Models\ApprovalWorkflow;
use App\Models\Item;
use App\Models\ItemHistory;
use App\Models\ItemLog;
use App\Models\PrHeader;
use App\Models\PrHistory;
use App\Models\PrLog;
use BackedEnum;
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


    protected static ?string $navigationLabel = 'Daftar Pengajuan PR';
    protected static ?string $pluralModelLabel = 'Daftar Pengajuan PR';
    protected static ?string $modelLabel = 'Pengajuan PR';

    public static function canViewAny(): bool
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Izinkan jika user memiliki role selain 'vessel_crew_requester'
        return $user && $user->roles()
            ->where('name', '!=', \App\Constants\RoleConstant::VESSEL_CREW_REQUESTER)
            ->exists();
    }

    public static function getEloquentQuery(): Builder
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $userRoleIds = $user->roles()->pluck('roles.id')->toArray();

        return parent::getEloquentQuery()
            ->whereIn('current_role_id', $userRoleIds)
            ->whereIn('pr_status', [
                \App\Constants\PrStatusConstant::WAITING_APPROVAL,
                \App\Constants\PrStatusConstant::APPROVED
            ]);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pengajuan')
                    ->icon('heroicon-o-information-circle')
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
                                    ->label('Status Saat Ini')
                                    ->badge()
                                    ->formatStateUsing(fn(string $state): string => \App\Constants\PrStatusConstant::getStatuses()[$state] ?? $state)
                                    ->color(fn(string $state): string => match ($state) {
                                        \App\Constants\PrStatusConstant::WAITING_APPROVAL, \App\Constants\PrStatusConstant::APPROVED => 'warning',
                                        \App\Constants\PrStatusConstant::REJECTED => 'danger',
                                        \App\Constants\PrStatusConstant::CONVERTED_TO_PO => 'success',
                                        \App\Constants\PrStatusConstant::CLOSED => 'gray',
                                        default => 'gray',
                                    }),

                                TextEntry::make('detail.vessel.name')
                                    ->label('Nama Kapal')
                                    ->icon('lucide-ship')
                                    ->weight('medium'),

                                TextEntry::make('detail.needs')
                                    ->label('Keperluan')
                                    ->icon('heroicon-m-wrench-screwdriver')
                                    ->placeholder('-'),
                                TextEntry::make('detail.request_date')
                                    ->label('Tgl Pengajuan (Server)')
                                    ->date('d M Y, H:i')
                                    ->icon('heroicon-m-calendar-days'),

                                TextEntry::make('detail.request_date_client')
                                    ->label('Tgl Pengajuan ()')
                                    ->date('d M Y, H:i')
                                    ->icon('heroicon-m-clock')
                                    ->color('gray'),
                            ]),
                    ])
                    ->collapsible(),

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
                    ->copyable(),
                \Filament\Tables\Columns\TextColumn::make('requester.name')
                    ->label('Pemohon')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('detail.vessel.name')
                    ->label('Kapal')
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('detail.request_date')
                    ->label('Tgl Pengajuan')
                    ->date('d M Y')
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('pr_status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => \App\Constants\PrStatusConstant::getStatuses()[$state] ?? $state)
                    ->color(fn(string $state): string => match ($state) {
                        \App\Constants\PrStatusConstant::WAITING_APPROVAL, \App\Constants\PrStatusConstant::APPROVED => 'warning',
                        \App\Constants\PrStatusConstant::REJECTED => 'danger',
                        \App\Constants\PrStatusConstant::CONVERTED_TO_PO => 'success',
                        \App\Constants\PrStatusConstant::CLOSED => 'gray',
                        default => 'gray',
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('pr_status')
                    ->label('Filter Status')
                    ->options(\App\Constants\PrStatusConstant::getStatuses()),
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make()
                    ->label('Tinjau')
                    ->modalHeading('Tinjau / Proses Pengajuan PR')
                    ->modalWidth('7xl')
                    ->modalSubmitAction(function (\Filament\Actions\Action $action): \Filament\Actions\Action {
                        return $action
                            ->label('Approval')
                            ->color('success')
                            ->icon('heroicon-o-check-circle');
                    })
                    ->action(fn(PrHeader $record) => static::handleApproval($record))
                    ->modalCancelActionLabel('Batal')
                    ->icon('heroicon-o-pencil'),
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

        // --- 2. Cari workflow aktif ---
        $workflow = ApprovalWorkflow::query()
            ->whereKey($header->approval_workflow_id)
            ->where('status', 'active')
            ->first();

        if (! $workflow) {
            Notification::make()->danger()->title('Workflow tidak aktif')->body('Approval workflow aktif untuk PR ini tidak ditemukan.')->send();
            return;
        }

        // --- 3. Pastikan current step ada di workflow ---
        $currentStep = $workflow->steps()->whereKey($header->current_step_id)->first();

        if (! $currentStep) {
            Notification::make()->danger()->title('Current step tidak ditemukan')->body('Step approval saat ini tidak ditemukan pada workflow aktif.')->send();
            return;
        }

        // --- 4. Otorisasi: role user harus sesuai current step ---
        if (! $user->roles()->where('roles.id', $currentStep->role_id)->exists()) {
            Notification::make()->danger()->title('Role tidak sesuai')->body('Role Anda tidak cocok dengan step approval saat ini.')->send();
            return;
        }

        // --- 5. Cari next step (step_order lebih besar dari current) ---
        $nextStep = $workflow->steps()
            ->where('step_order', '>', $currentStep->step_order)
            ->orderBy('step_order')
            ->first();

        if (! $nextStep) {
            Notification::make()->danger()->title('Step berikutnya tidak ditemukan')->body('Step approval berikutnya tidak ditemukan di workflow aktif.')->send();
            return;
        }

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
                DB::table('pr_headers')
                    ->where('id', $header->id)
                    ->update([
                        'pr_status'      => PrStatusConstant::APPROVED,
                        'current_role_id' => $nextStep->role_id,
                        'current_step_id' => $nextStep->id,
                        'approver_id'    => $user->id,
                        'approved_at'    => $now,
                        'updated_at'     => $now,
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
                    'detail_description' => $detail?->description,
                    'payload' => [
                        // Snapshot quantity setiap item pada saat approval
                        'edited_quantities' => $latestItems
                            ->mapWithKeys(fn(Item $item): array => [$item->id => $item->quantity])
                            ->all(),
                        'next_step_id' => $header->current_step_id,
                        'next_role_id' => $header->current_role_id,
                    ],
                ]);

                // 7c. Buat riwayat header ke pr_histories (snapshot status header)
                PrHistory::create([
                    'batch_id' => $batchId,
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
