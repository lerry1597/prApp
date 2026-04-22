<?php

namespace App\Filament\Widgets\Admin;

use Filament\Widgets\ChartWidget;

class PrRequestByVessel extends ChartWidget
{
    protected ?string $heading = 'Pr Request By Vessel';

    protected function getData(): array
    {

        return [
            'datasets' => [
                [
                    'label' => 'Purchase Requests',
                    'data' => [0, 10, 5, 2, 21, 32, 45, 74, 65, 45, 77, 89],
                    'backgroundColor' => '#eb8934',
                    'borderColor' => '#9BD0F5',
                ],
            ],
            'labels' => ['Vessel 1', 'Vessel 2', 'Vessel 3', 'Vessel 4', 'Vessel 5', 'Vessel 6', 'Vessel 7', 'Vessel 8', 'Vessel 9', 'Vessel 10', 'Vessel 11', 'Vessel 12'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
