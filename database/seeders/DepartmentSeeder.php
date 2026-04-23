<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Procurement',
                'code' => 'PRC',
                'description' => 'Departemen yang bertanggung jawab atas pengadaan barang dan jasa perusahaan secara efisien dan transparan.',
                'status' => 'active',
            ],
            [
                'name' => 'Technical',
                'code' => 'TCH',
                'description' => 'Departemen yang mengelola aspek teknis, pemeliharaan, dan operasional infrastruktur atau armada.',
                'status' => 'active',
            ],
        ];

        foreach ($departments as $dept) {
            Department::updateOrCreate(['code' => $dept['code']], $dept);
        }
    }
}
