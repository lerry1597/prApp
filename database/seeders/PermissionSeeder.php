<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'create',
                'title' => 'Create',
                'description' => 'Izin untuk membuat data baru.',
            ],
            [
                'name' => 'edit',
                'title' => 'Edit',
                'description' => 'Izin untuk mengubah data yang ada.',
            ],
            [
                'name' => 'view',
                'title' => 'View',
                'description' => 'Izin untuk melihat data.',
            ],
            [
                'name' => 'delete',
                'title' => 'Delete',
                'description' => 'Izin untuk menghapus data.',
            ],
            [
                'name' => 'revision',
                'title' => 'Revision',
                'description' => 'Izin untuk mengajukan revisi data.',
            ],
            [
                'name' => 'approve',
                'title' => 'Approve',
                'description' => 'Izin untuk memberikan persetujuan (approval).',
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['name' => $permission['name']], $permission);
        }
    }
}
