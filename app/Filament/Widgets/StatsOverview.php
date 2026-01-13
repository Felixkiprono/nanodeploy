<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Servers', 2)
                ->description('Active servers')
                ->icon('heroicon-o-server-stack'),

            Stat::make('Sites', 5)
                ->description('Total sites')
                ->icon('heroicon-o-globe-alt'),

            Stat::make('SSL Coverage', '100%')
                ->description('All sites secured')
                ->icon('heroicon-o-lock-closed'),

            Stat::make('Insights', 0)
                ->description('No issues detected')
                ->icon('heroicon-o-chart-bar'),
        ];
    }
}
