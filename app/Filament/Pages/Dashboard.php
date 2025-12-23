<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\IncomeFiltersWidget;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\IncomeChart;
use App\Filament\Widgets\OrdersChart;
use App\Filament\Widgets\RecentOrders;

class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [
            IncomeFiltersWidget::class,
            StatsOverview::class,
            IncomeChart::class,
            OrdersChart::class,
            RecentOrders::class,
        ];
    }
}
