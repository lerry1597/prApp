<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'super_admin',
                'title' => 'Super Administrator',
                'description' => 'Akses penuh ke seluruh sistem.',
                'status' => 'active',
            ],
            [
                'name' => 'procurement_officer',
                'title' => 'Procurement Officer',
                'description' => 'Pengadaan barang dan jasa',
                'status' => 'active',
            ],
            [
                'name' => 'technical_approver',
                'title' => 'Technical Approver',
                'description' => 'Pengguna standar dengan akses terbatas.',
                'status' => 'active',
            ],
            [
                'name' => 'vessel_crew_requester',
                'title' => 'Crew Requester',
                'description' => 'Kru kapal yang meminta barang dan jasa',
                'status' => 'active',
            ]
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['name' => $role['name']], $role);
        }
    }
}
