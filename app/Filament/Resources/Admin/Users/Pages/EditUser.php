<?php

namespace App\Filament\Resources\Admin\Users\Pages;

use App\Constants\RoleConstant;
use App\Filament\Resources\Admin\Users\Schemas\CrewUserForm;
use App\Filament\Resources\Admin\Users\Schemas\GeneralUserForm;
use App\Filament\Resources\Admin\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected ?int $selectedCompanyId = null;

    public function form(Schema $schema): Schema
    {
        return $this->isCrewUser($this->getRecord())
            ? CrewUserForm::configure($schema)
            : GeneralUserForm::configure($schema);
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (! $this->isCrewUser($this->getRecord())) {
            $data['company_id'] = $this->getRecord()->companies()->value('companies.id');
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (! $this->isCrewUser($this->getRecord())) {
            $this->selectedCompanyId = isset($data['company_id']) ? (int) $data['company_id'] : null;
            unset($data['company_id']);
        }

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return DB::transaction(function () use ($record, $data) {
            return parent::handleRecordUpdate($record, $data);
        });
    }

    protected function afterSave(): void
    {
        if ($this->isCrewUser($this->record)) {
            $companyId = $this->record->vessel?->company_id;

            if ($companyId) {
                $this->record->companies()->sync([
                    $companyId => ['status' => 'active'],
                ]);
            }

            return;
        }

        if ($this->selectedCompanyId) {
            $this->record->companies()->sync([
                $this->selectedCompanyId => ['status' => 'active'],
            ]);
        }
    }

    protected function isCrewUser(Model $record): bool
    {
        return $record->roles()
            ->where('name', RoleConstant::VESSEL_CREW_REQUESTER)
            ->exists();
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
