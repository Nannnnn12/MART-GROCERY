<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class IncomeChart extends ChartWidget
{
    protected static ?int $sort = 3;

    protected $listeners = ['pendapatan-filter-updated' => '$refresh'];

    public function getHeading(): ?string
    {
        return 'Grafik Pendapatan';
    }

    protected function getData(): array
    {
        $filters   = session('pendapatan_filter', []);
        $startDate = $filters['startDate'] ?? null;
        $endDate   = $filters['endDate'] ?? null;
        $year      = $filters['year'] ?? null;
        $month     = $filters['month'] ?? null;

        $labels = [];
        $values = [];

        // =====================
        // RANGE DATE
        // =====================
        if ($startDate && $endDate) {
            $period = Carbon::parse($startDate)->daysUntil($endDate);

            foreach ($period as $date) {
                $income = Transaction::where('status', 'delivered')
                    ->whereDate('created_at', $date)
                    ->sum(DB::raw('total - shipping_cost'));

                $labels[] = $date->format('d M');
                $values[] = (float) $income;
            }

        // =====================
        // YEAR + MONTH
        // =====================
        } elseif ($year && $month) {
            $days = Carbon::create($year, $month)->daysInMonth;

            for ($d = 1; $d <= $days; $d++) {
                $date = Carbon::create($year, $month, $d);

                $income = Transaction::where('status', 'delivered')
                    ->whereDate('created_at', $date)
                    ->sum(DB::raw('total - shipping_cost'));

                $labels[] = $date->format('d');
                $values[] = (float) $income;
            }

        // =====================
        // YEAR ONLY
        // =====================
        } elseif ($year) {
            for ($m = 1; $m <= 12; $m++) {
                $income = Transaction::where('status', 'delivered')
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $m)
                    ->sum(DB::raw('total - shipping_cost'));

                $labels[] = Carbon::create($year, $m)->format('M');
                $values[] = (float) $income;
            }

        // =====================
        // ALL DATA
        // =====================
        } else {
            $firstYear = Transaction::where('status', 'delivered')
                ->orderBy('created_at')
                ->value('created_at');

            $startYear = $firstYear
                ? Carbon::parse($firstYear)->year
                : now()->year;

            for ($y = $startYear; $y <= now()->year; $y++) {
                $income = Transaction::where('status', 'delivered')
                    ->whereYear('created_at', $y)
                    ->sum(DB::raw('total - shipping_cost'));

                $labels[] = (string) $y;
                $values[] = (float) $income;
            }
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Pendapatan (Rp)',
                    'data' => $values,
                    'fill' => true, // ✅ FILL
                    'borderWidth' => 2,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
