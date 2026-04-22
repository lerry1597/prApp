<?php

declare(strict_types=1);

namespace App\Service\Admin;

class DashboardGetStatsService
{
    /**
     * Ambil statistik dashboard dasar.
     *
     * @return array<string,mixed>
     */
    public function getStats(): array
    {
        // Implementasi sementara — kembalikan array kosong untuk sekarang.
        return [
            'users' => [
                'total' => 150,
                'crew' => 80,
                'teknikal' => 40,
                'purchasing' => 30,
                'trend_percent' => 12,
                'unverified' => 5,
            ],
            'vessels' => [
                'total' => 25,
                'tugboat' => 15,
                'tanker' => 10,
                'off_hire' => 2, // Kapal yang butuh tindakan
            ],
            'companies' => [
                'total' => 3,
                'expiring_docs' => 1, // Izin yang hampir expired
            ]
        ];
    }
}
