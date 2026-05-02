<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            DepartmentSeeder::class,
            PositionSeeder::class,
            ItemCategorySeeder::class,
            ApprovalWorkflowSeeder::class,
            AdminUserSeeder::class,
        ]);

        // User::factory(10)->create();

        // User::updateOrCreate(
        //     ['username' => 'testuser'],
        //     [
        //         'name' => 'Test User',
        //         'email' => 'test@example.com',
        //         'password' => 'password', // The model cast 'hashed' will handle this
        //     ]
        // );
    }
}
