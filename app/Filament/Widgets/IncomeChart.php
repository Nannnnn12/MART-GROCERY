<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class IncomeChart extends ChartWidget
{

    protected static ?int $sort = 3;

    public ?string $filter = 'month';

    public function getHeading(): ?string
    {
        return 'Grafik Pendapatan';
    }

    public function getColumnSpan(): int
    {
        return 1;
    }

    protected function getFilters(): ?array
    {
        return [
            'all' => 'Semua',
            'day' => 'Hari Ini',
            'month' => 'Bulan Ini',
            'year' => 'Tahun Ini',
        ];
    }

    protected function getData(): array
    {
        $data = [];

        $query = Transaction::query();

        switch ($this->filter) {
            case 'all':
                $data = [
                    'labels' => [],
                    'datasets' => [
                        [
                            'label' => 'Total Income',
                            'data' => [],
                        ],
                    ],
                ];

                $currentYear = now()->year;
                $startYear = 2020;

                // Get income data for years with transactions
                $incomeData = Transaction::where('status', 'delivered')
                    ->selectRaw('YEAR(created_at) as year, SUM(total) as income')
                    ->whereYear('created_at', '>=', $startYear)
                    ->groupByRaw('YEAR(created_at)')
                    ->pluck('income', 'year')
                    ->toArray();

                // Loop from 2020 to current year, including years with no data
                for ($year = $startYear; $year <= $currentYear; $year++) {
                    $data['labels'][] = (string) $year;
                    $data['datasets'][0]['data'][] = (float) ($incomeData[$year] ?? 0);
                }
                break;

            case 'day':
                $query->whereDate('created_at', today());
                // Show income per hour for today
                for ($hour = 0; $hour < 24; $hour++) {
                    $income = Transaction::whereDate('created_at', today())
                        ->whereRaw('HOUR(created_at) = ?', [$hour])
                        ->where('status', 'delivered')
                        ->sum('total');
                    $data['labels'][] = $hour . ':00';
                    $data['datasets'][0]['data'][] = (float) $income;
                }
                break;

            case 'month':
                // Show income per day for current month
                $daysInMonth = now()->daysInMonth;
                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $date = now()->setDay($day);
                    $income = Transaction::whereDate('created_at', $date)->where('status', 'delivered')->sum('total');
                    $data['labels'][] = $date->format('d');
                    $data['datasets'][0]['data'][] = (float) $income;
                }
                break;

            case 'year':
                // Show income per month for current year
                for ($month = 1; $month <= 12; $month++) {
                    $date = Carbon::create(now()->year, $month, 1);
                    $income = Transaction::whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->where('status', 'delivered')
                        ->sum('total');
                    $data['labels'][] = $date->format('M');
                    $data['datasets'][0]['data'][] = (float) $income;
                }
                break;
        }

        $data['datasets'][0]['label'] = 'Pendapatan (Rp)';
        $data['datasets'][0]['backgroundColor'] = 'rgba(34, 197, 94, 0.2)';
        $data['datasets'][0]['borderColor'] = 'rgba(34, 197, 94, 1)';
        $data['datasets'][0]['borderWidth'] = 2;

        return $data;
    }

    protected function getType(): string
    {
        return 'line';
    }
}
