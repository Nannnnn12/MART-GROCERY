<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Transaction;
use App\Models\TransactionItem;

class IncomeStatsOverview extends StatsOverviewWidget
{
    protected static bool $isDiscovered = false;

    protected function getColumns(): int
    {
        return 4;
    }

    protected function getStats(): array
    {
        $today     = now()->startOfDay();

        $incomeToday  = Transaction::whereDate('created_at', now())->sum('total');

        $totalSales   = Transaction::sum('total');
        $totalTrans   = Transaction::count();
        $totalProductsSold = TransactionItem::sum('quantity');

        return [
            Stat::make('Income Hari Ini', 'Rp ' . number_format($incomeToday, 0, ',', '.'))
                ->icon('heroicon-o-banknotes')
                ->color('success'),

            Stat::make('Total Sales', 'Rp ' . number_format($totalSales, 0, ',', '.'))
                ->icon('heroicon-o-chart-bar')
                ->color('primary'),

            Stat::make('Total Transaksi', $totalTrans)
                ->icon('heroicon-o-shopping-cart')
                ->color('primary'),

            Stat::make('Total Produk Terjual', number_format($totalProductsSold, 0, ',', '.'))
                ->icon('heroicon-o-cube')
                ->color('warning'),
        ];
    }
}
