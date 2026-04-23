<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = [
            [
                'name' => 'Kepala Departemen',
                'code' => 'HOD',
                'description' => 'Pimpinan tertinggi di tingkat departemen.',
            ],
            [
                'name' => 'Supervisor / Kepala Tim',
                'code' => 'SUP',
                'description' => 'Bertanggung jawab atas pengawasan tim operasional.',
            ],
            [
                'name' => 'Staf',
                'code' => 'STF',
                'description' => 'Pelaksana tugas administratif dan operasional harian.',
            ],
            [
                'name' => 'Operator',
                'code' => 'OPR',
                'description' => 'Pelaksana teknis di lapangan.',
            ],
        ];

        foreach ($positions as $position) {
            Position::updateOrCreate(['code' => $position['code']], $position);
        }
    }
}
