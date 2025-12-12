<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Products', Product::count())
                ->description('Total number of products')
                ->descriptionIcon('heroicon-m-cube')
                ->color('success'),
            Stat::make('Total Orders', \App\Models\Transaction::count())
                ->description('Total number of orders')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary'),
            Stat::make('Users with Role', User::whereNotNull('role')->count())
                ->description('Users with assigned roles')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('warning'),
        ];
    }
}
