<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
        $year      = $filters['year'] ?? null;   // null = semua tahun
        $month     = $filters['month'] ?? null;  // null = semua bulan

        // ===================== BASE QUERY =====================
        // income = total - shipping_cost
        $baseQuery = Transaction::where('status', 'delivered')
            ->selectRaw('SUM(total - shipping_cost) as income');

        // ===================== DATE RANGE =====================
        if ($startDate && $endDate) {

            $rangeQuery = (clone $baseQuery)
                ->whereBetween('created_at', [$startDate, $endDate]);

            $incomeToday     = $rangeQuery->value('income') ?? 0;
            $incomeThisMonth = $incomeToday;
            $incomeThisYear  = $incomeToday;

            $label =
                Carbon::parse($startDate)->translatedFormat('d F Y')
                . ' – '
                . Carbon::parse($endDate)->translatedFormat('d F Y');

            $dayLabel = $monthLabel = $yearLabel = $label;

        } else {

            // ===================== TAHUNAN =====================
            $yearQuery = (clone $baseQuery);

            if ($year) {
                $yearQuery->whereYear('created_at', $year);
            }

            // ===================== BULANAN =====================
            $monthQuery = (clone $yearQuery);

            if ($month) {
                $monthQuery->whereMonth('created_at', $month);
            }

            // ===================== HARIAN =====================
            $dayQuery = (clone $monthQuery)
                ->whereDate('created_at', now());

            // ===================== HITUNG =====================
            $incomeThisYear  = $yearQuery->value('income') ?? 0;
            $incomeThisMonth = $monthQuery->value('income') ?? 0;
            $incomeToday     = $dayQuery->value('income') ?? 0;

            // ===================== START YEAR (DATA PERTAMA) =====================
            $firstYear = Transaction::where('status', 'delivered')
                ->orderBy('created_at')
                ->value('created_at');

            $startYear = $firstYear
                ? Carbon::parse($firstYear)->year
                : now()->year;

            // ===================== LABEL =====================
            $dayLabel = 'Hari ini (' . now()->translatedFormat('d F Y') . ')';

            $monthLabel = $month
                ? 'Bulan ' . Carbon::create($year ?? now()->year, $month)->translatedFormat('F Y')
                : 'Semua Bulan';

            $yearLabel = $year
                ? 'Tahun ' . $year
                : 'Tahun ' . $startYear . ' – ' . now()->year;
        }

        return [
            Stat::make(
                'Income Harian',
                'Rp ' . number_format($incomeToday, 0, ',', '.')
            )->description($dayLabel),

            Stat::make(
                'Income Bulanan',
                'Rp ' . number_format($incomeThisMonth, 0, ',', '.')
            )->description($monthLabel),

            Stat::make(
                'Income Tahunan',
                'Rp ' . number_format($incomeThisYear, 0, ',', '.')
            )->description($yearLabel),
        ];
    }
}
