<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $incomeToday = Transaction::where('status', 'delivered')
            ->whereDate('created_at', now())
            ->sum('total');

        $incomeThisMonth = Transaction::where('status', 'delivered')
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('total');
        return [
            Stat::make('Total Products', Product::count())
                ->description('Total number of products')
                ->descriptionIcon('heroicon-m-cube')
                ->color('success'),
            Stat::make('Total Orders', \App\Models\Transaction::count())
                ->description('Total number of orders')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary'),
            Stat::make('Income Hari Ini', 'Rp ' . number_format($incomeToday, 0, ',', '.'))
                ->icon('heroicon-o-banknotes')
                ->color('success'),
            Stat::make(
                'Income Bulan Ini',
                'Rp ' . number_format($incomeThisMonth, 0, ',', '.')
            )
                ->description('Bulan ' . now()->translatedFormat('F Y'))
                ->icon('heroicon-o-calendar-days')
                ->color('warning'),
        ];
    }
}
