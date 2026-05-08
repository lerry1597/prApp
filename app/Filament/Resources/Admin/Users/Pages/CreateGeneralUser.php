<?php

namespace App\Filament\Resources\Admin\Users\Pages;

use App\Filament\Resources\Admin\Users\Schemas\GeneralUserForm;
use App\Filament\Resources\Admin\Users\UserResource;
use App\Models\User;
use App\Service\PasswordNotificationService;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreateGeneralUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected static ?string $title = 'Tambah Pengguna Umum';

    /** Plain-text password captured before hashing. */
    protected ?string $plainPassword = null;

    /** Company ID captured from form (not a real User column). */
    protected ?int $selectedCompanyId = null;

    public function form(Schema $schema): Schema
    {
        return GeneralUserForm::configure($schema);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Capture plain password before Laravel hashes it via model cast.
        $this->plainPassword = $data['password'] ?? null;

        // Extract company_id — it's handled via the company_user pivot, not a User column.
        $this->selectedCompanyId = isset($data['company_id']) ? (int) $data['company_id'] : null;
        unset($data['company_id']); // prevent passing to User::create()

        // Generate user_code (will be overwritten with actual ID in afterCreate).
        $data['user_code'] = 'USR-' . str_pad(
            (User::withTrashed()->max('id') ?? 0) + 1,
            6, '0', STR_PAD_LEFT
        );

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(fn () => parent::handleRecordCreation($data));
    }

    protected function afterCreate(): void
    {
        // Finalize user_code with actual record ID.
        $this->record->update([
            'user_code' => 'USR-' . str_pad($this->record->id, 6, '0', STR_PAD_LEFT),
        ]);

        // Attach company to the company_user pivot.
        if ($this->selectedCompanyId) {
            $this->record->companies()->syncWithoutDetaching([
                $this->selectedCompanyId => ['status' => 'active'],
            ]);
        }

        // Dispatch fire-and-forget password notification.
        if ($this->plainPassword) {
            PasswordNotificationService::dispatch($this->record, $this->plainPassword);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
