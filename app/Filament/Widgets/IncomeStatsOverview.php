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

        $incomeToday  = Transaction::where('status', 'delivered')->whereDate('created_at', now())->sum('total');
        $incomeThisMonth = Transaction::where('status', 'delivered')->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('total');
        $incomeThisYear = Transaction::where('status', 'delivered')->whereYear('created_at', now()->year)->sum('total');
        $totalSales   = Transaction::where('status', 'delivered')->sum('total');

        return [
            Stat::make('Income Hari Ini', 'Rp ' . number_format($incomeToday, 0, ',', '.'))
                ->icon('heroicon-o-banknotes')
                ->color('success'),

            Stat::make('Income Bulan Ini', 'Rp ' . number_format($incomeThisMonth, 0, ',', '.'))
                ->icon('heroicon-o-calendar-days')
                ->color('info'),

            Stat::make('Income Tahun Ini', 'Rp ' . number_format($incomeThisYear, 0, ',', '.'))
                ->icon('heroicon-o-chart-bar')
                ->color('primary'),

            Stat::make('Total Pendapatan', 'Rp ' . number_format($totalSales, 0, ',', '.'))
                ->icon('heroicon-o-currency-dollar')
                ->color('success'),
        ];
    }
}
