<?php

namespace App\Filament\Resources\Admin\Users\Pages;

use App\Filament\Resources\Admin\Users\Schemas\CrewUserForm;
use App\Filament\Resources\Admin\Users\UserResource;
use App\Models\User;
use App\Models\Vessel;
use App\Service\PasswordNotificationService;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreateCrewUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected static ?string $title = 'Tambah Kru Kapal';

    /** Plain-text password captured before hashing. */
    protected ?string $plainPassword = null;

    public function form(Schema $schema): Schema
    {
        return CrewUserForm::configure($schema);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Capture plain password before Laravel hashes it via model cast.
        $this->plainPassword = $data['password'] ?? null;

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

        // Auto-attach the vessel's company to the company_user pivot.
        if ($this->record->vessel_id) {
            $vessel = Vessel::find($this->record->vessel_id);
            if ($vessel && $vessel->company_id) {
                $this->record->companies()->syncWithoutDetaching([
                    $vessel->company_id => ['status' => 'active'],
                ]);
            }
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
