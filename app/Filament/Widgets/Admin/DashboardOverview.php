<?php

namespace App\Filament\Widgets\Admin;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Ringkasan';

    protected ?string $description = 'Menampilkan statistik utama pengguna, kapal, dan perusahaan untuk mendukung monitoring dan pengambilan keputusan.';

    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $service = new \App\Service\Admin\DashboardGetStatsService();
        $data = $service->getStats();

        return [
            // 1. KONDISI SEKARANG (Status Armada & PT)
            Stat::make('Kondisi Armada', $data['vessels']['total'] . ' Unit')
                ->description("Tugboat: {$data['vessels']['tugboat']} | Tanker: {$data['vessels']['tanker']}")
                ->icon('lucide-ship')
                ->color('primary'),

            // 2. APA YANG BERUBAH / TREND (User Growth)
            Stat::make('User', $data['users']['total'] . ' Personel')
                ->description("Naik {$data['users']['trend_percent']}% bulan ini")
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->icon('lucide-users')
                ->color('success'),

            // 3. APA YANG PERLU DITINDAKLANJUTI (Alerts)
            Stat::make('Tindakan Dibutuhkan', ($data['vessels']['off_hire'] + $data['companies']['expiring_docs']) . ' Isu')
                ->description("{$data['vessels']['off_hire']} Kapal Off-Hire | {$data['companies']['expiring_docs']} Izin Expired")
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->icon('lucide-alert-triangle')
                ->color('danger'),
        ];
    }
}
