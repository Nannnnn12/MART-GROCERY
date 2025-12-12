<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class OrdersChart extends ChartWidget
{
    protected static ?int $sort = 2;

    public function getHeading(): ?string
    {
        return 'Grafik Pesanan Per Bulan';
    }

    public function getColumnSpan(): int
    {
        return 2;
    }

    protected function getData(): array
    {
        $data = [];

        // Get data for the last 12 months
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = Transaction::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $data['labels'][] = $date->format('M Y');
            $data['datasets'][0]['data'][] = $count;
        }

        $data['datasets'][0]['label'] = 'Orders';
        $data['datasets'][0]['backgroundColor'] = 'rgba(54, 162, 235, 0.2)';
        $data['datasets'][0]['borderColor'] = 'rgba(54, 162, 235, 1)';
        $data['datasets'][0]['borderWidth'] = 1;

        return $data;
    }

    protected function getType(): string
    {
        return 'line';
    }
}
