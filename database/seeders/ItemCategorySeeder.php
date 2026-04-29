<?php

namespace Database\Seeders;

use App\Models\ItemCategory;
use Illuminate\Database\Seeder;

class ItemCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Suku Cadang', 'code' => 'SPP'],
            ['name' => 'Peralatan', 'code' => 'TLS'],
            ['name' => 'Bahan Habis Pakai', 'code' => 'CSM'],
            ['name' => 'Oli & Gemuk', 'code' => 'OAG'],
            ['name' => 'Tali', 'code' => 'TLI'],
            ['name' => 'Barang Kelistrikan', 'code' => 'ELC'],
            ['name' => 'Cat & Anoda', 'code' => 'PNA'],
            ['name' => 'IT', 'code' => 'IT'],
            ['name' => 'Filter', 'code' => 'FLT'],
            ['name' => 'Perlengkapan', 'code' => 'EQP'],
        ];

        foreach ($categories as $category) {
            ItemCategory::updateOrCreate(
                ['code' => $category['code']],
                [
                    'name' => $category['name'],
                    'status' => 'active',
                ]
            );
        }
    }
}
