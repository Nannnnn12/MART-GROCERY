<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CustomerStatsOverview extends BaseWidget
{
    protected static bool $isDiscovered = false;

    public ?int $customerId = null; // ← Tambah ini

    protected function getStats(): array
    {
        $id = $this->customerId;

        if (!$id) {
            return [];
        }

        return [
            Stat::make(
                'Total Pesanan',
                \App\Models\Transaction::where('customer_id', $id)->count()
            )
                ->description('Total number of orders')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary'),

            Stat::make(
                'Total Pengeluaran',
                'Rp ' . number_format(
                    \App\Models\Transaction::where('customer_id', $id)->sum('total'),
                    0, ',', '.'
                )
            )
                ->description('Total spending')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),

            Stat::make(
                'Total Ulasan',
                \App\Models\Review::where('user_id', $id)->count()
            )
                ->description('Total reviews')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning'),
        ];
    }
}
