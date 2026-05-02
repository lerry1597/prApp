<?php

namespace Database\Seeders;

use App\Constants\RoleConstant;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['username' => 'admin'],
            [
                'user_code' => 'ADM000000001',
                'name' => 'Administrator',
                'email' => 'admin@example.com',
                'password' => 'password',
                'is_primary' => true,
                'status' => 'active',
            ]
        );

        $superAdminRole = Role::where('name', RoleConstant::SUPER_ADMIN)->first();

        if (!$superAdminRole) {
            return;
        }

        $user->roles()->syncWithoutDetaching([
            $superAdminRole->id => [
                'assigned_by' => $user->id,
                'assigned_at' => now(),
            ],
        ]);
    }
}
