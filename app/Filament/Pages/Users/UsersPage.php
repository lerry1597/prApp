<?php

namespace App\Filament\Pages\Users;

use App\Models\User;
use Filament\Pages\Page;

class UsersPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static string $view = 'filament.pages.users.users-page';
    protected static ?string $navigationLabel = 'Users';
    protected static ?string $slug = 'users';
    protected static ?int $navigationSort = 5;

    public function getUsers()
    {
        return User::query()->orderBy('name')->get();
    }
}
