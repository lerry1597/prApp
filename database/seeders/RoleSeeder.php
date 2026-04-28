<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

use App\Constants\RoleConstant;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => RoleConstant::SUPER_ADMIN,
                'title' => 'Super Administrator',
                'description' => 'Akses penuh ke seluruh sistem.',
                'status' => 'active',
            ],
            [
                'name' => RoleConstant::PROCUREMENT_OFFICER,
                'title' => 'Procurement Officer',
                'description' => 'Pengadaan barang dan jasa',
                'status' => 'active',
            ],
            [
                'name' => RoleConstant::TECHNICAL_APPROVER,
                'title' => 'Technical Approver',
                'description' => 'Pengguna standar dengan akses terbatas.',
                'status' => 'active',
            ],
            [
                'name' => RoleConstant::VESSEL_CREW_REQUESTER,
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
