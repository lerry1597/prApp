<?php

namespace App\Filament\Resources\Admin\Users;

use App\Filament\Resources\Admin\Users\Pages\CreateCrewUser;
use App\Filament\Resources\Admin\Users\Pages\CreateGeneralUser;
use App\Filament\Resources\Admin\Users\Pages\CreateUser;
use App\Filament\Resources\Admin\Users\Pages\EditUser;
use App\Filament\Resources\Admin\Users\Pages\ListUsers;
use App\Filament\Resources\Admin\Users\Pages\ViewUser;
use App\Filament\Resources\Admin\Users\Schemas\UserForm;
use App\Filament\Resources\Admin\Users\Schemas\UserInfolist;
use App\Filament\Resources\Admin\Users\Tables\UsersTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?string $navigationLabel = 'Kelola Pengguna';

    protected static ?string $modelLabel = 'Pengguna';

    protected static ?string $pluralModelLabel = 'Pengguna';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return UserInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereDoesntHave('roles', function (Builder $query) {
                $query->where('name', \App\Constants\RoleConstant::SUPER_ADMIN);
            });
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'         => ListUsers::route('/'),
            'create'        => CreateUser::route('/create'),
            'create-crew'   => CreateCrewUser::route('/create/crew'),
            'create-general' => CreateGeneralUser::route('/create/general'),
            'view'          => ViewUser::route('/{record}'),
            'edit'          => EditUser::route('/{record}/edit'),
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
