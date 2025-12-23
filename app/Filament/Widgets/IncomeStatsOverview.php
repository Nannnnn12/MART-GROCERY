<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use Carbon\Carbon;

class IncomeStatsOverview extends StatsOverviewWidget
{
    protected $listeners = ['pendapatan-filter-updated' => '$refresh'];
    protected static bool $isDiscovered = false;

    protected function getColumns(): int
    {
        return 3;
    }

    protected function getStats(): array
    {


        $filters   = session('pendapatan_filter', []);
        $startDate = $filters['startDate'] ?? null;
        $endDate   = $filters['endDate'] ?? null;

        $baseQuery = Transaction::where('status', 'delivered');

        if ($startDate && $endDate) {
            $incomeToday = (clone $baseQuery)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('total');

            $incomeThisMonth = $incomeToday;
            $incomeThisYear  = $incomeToday;

            $rangeLabel = 'Dari ' . Carbon::parse($startDate)->translatedFormat('d F Y') . ' sampai ' . Carbon::parse($endDate)->translatedFormat('d F Y');
        } else {
            // ================= DEFAULT / START DATE =================
            $date = $startDate
                ? Carbon::parse($startDate)
                : now();

            $incomeToday = (clone $baseQuery)
                ->whereDate('created_at', $date)
                ->sum('total');

            $incomeThisMonth = (clone $baseQuery)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('total');

            $incomeThisYear = (clone $baseQuery)
                ->whereYear('created_at', $date->year)
                ->sum('total');

            $dayLabel   = 'Dari ' . $date->translatedFormat('d F Y') . ' sampai ' . $date->translatedFormat('d F Y');
            $monthLabel = 'Dari ' . $date->startOfMonth()->translatedFormat('d F Y') . ' sampai ' . $date->endOfMonth()->translatedFormat('d F Y');
            $yearLabel  = 'Dari ' . $date->startOfYear()->translatedFormat('d F Y') . ' sampai ' . $date->endOfYear()->translatedFormat('d F Y');
        }

        return [
            Stat::make(
                'Income Harian',
                'Rp ' . number_format($incomeToday, 0, ',', '.')
            )
                ->description($startDate && $endDate ? $rangeLabel : $dayLabel),

            Stat::make(
                'Income Bulanan',
                'Rp ' . number_format($incomeThisMonth, 0, ',', '.')
            )
                ->description($startDate && $endDate ? $rangeLabel : $monthLabel),

            Stat::make(
                'Income Tahunan',
                'Rp ' . number_format($incomeThisYear, 0, ',', '.')
            )
                ->description($startDate && $endDate ? $rangeLabel : $yearLabel),
        ];
    }
}
