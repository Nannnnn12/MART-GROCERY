<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class OrdersChart extends ChartWidget
{
    protected static ?int $sort = 2;

    protected $listeners = ['pendapatan-filter-updated' => '$refresh'];

    public function getHeading(): ?string
    {
        return 'Grafik Pesanan';
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

        // ===================== RANGE DATE =====================
        if ($startDate && $endDate) {

            $period = Carbon::parse($startDate)->daysUntil($endDate);

            foreach ($period as $date) {
                $labels[] = $date->format('d M');
                $values[] = Transaction::whereDate('created_at', $date)->count();
            }

        // ===================== YEAR + MONTH =====================
        } elseif ($year && $month) {

            $days = Carbon::create($year, $month)->daysInMonth;

            for ($day = 1; $day <= $days; $day++) {
                $date = Carbon::create($year, $month, $day);
                $labels[] = $date->format('d');
                $values[] = Transaction::whereDate('created_at', $date)->count();
            }

        // ===================== YEAR ONLY =====================
        } elseif ($year) {

            for ($m = 1; $m <= 12; $m++) {
                $labels[] = Carbon::create($year, $m)->format('M');
                $values[] = Transaction::whereYear('created_at', $year)
                    ->whereMonth('created_at', $m)
                    ->count();
            }

        // ===================== DEFAULT (SEMUA TAHUN) =====================
        } else {

            $firstYear = Transaction::min(
                \DB::raw('YEAR(created_at)')
            ) ?? now()->year;

            for ($y = $firstYear; $y <= now()->year; $y++) {
                $labels[] = (string) $y;
                $values[] = Transaction::whereYear('created_at', $y)->count();
            }
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Jumlah Pesanan',
                    'data' => $values,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.4)',
                    'borderColor' => 'rgba(59, 130, 246, 1)',
                    'borderWidth' => 2,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
