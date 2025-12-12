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
        return 2;
    }

    protected function getFilters(): ?array
    {
        return [
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
            case 'day':
                $query->whereDate('created_at', today());
                // Show income per hour for today
                for ($hour = 0; $hour < 24; $hour++) {
                    $income = Transaction::whereDate('created_at', today())
                        ->whereRaw('HOUR(created_at) = ?', [$hour])
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
                    $income = Transaction::whereDate('created_at', $date)->sum('total');
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
