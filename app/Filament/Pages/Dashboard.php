<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Widgets\StatsOverview;
use Barn2\Plugin\Posts_Table_Search_Sort\Admin\Wizard\Steps\Welcome;
use Filament\Support\Icons\Heroicon;
use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\WelcomeWidget;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationLabel = 'Dashboard';



    // public function getHeaderWidgets(): array
    // {
    //     return [
    //         WelcomeWidget::class,
    //     ];
    // }

    // public function getWidgets(): array
    // {
    //     return [
    //         StatsOverview::class,
    //     ];
    // }



    public function getTitle(): string
    {
        return 'Dashboard';
    }
}
