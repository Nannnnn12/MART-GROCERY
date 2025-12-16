<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Transaction;
use App\Models\TransactionItem;

class IncomeStatsOverview2 extends StatsOverviewWidget
{
    protected static bool $isDiscovered = false;

    protected function getColumns(): int
    {
        return 3;
    }

    protected function getStats(): array
    {
        $totalTrans   = Transaction::count();
        $totalDeliveredTrans = Transaction::where('status', 'delivered')->count();
        $totalProductsSold = TransactionItem::sum('quantity');

        return [
            Stat::make('Total Transaksi', $totalTrans)
                ->icon('heroicon-o-shopping-cart')
                ->color('primary'),

            Stat::make('Transaksi Terkirim', $totalDeliveredTrans)
                ->icon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Total Produk Terjual', number_format($totalProductsSold, 0, ',', '.'))
                ->icon('heroicon-o-cube')
                ->color('warning'),
        ];
    }
}
