<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected $listeners = ['pendapatan-filter-updated' => '$refresh'];

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $filters   = session('pendapatan_filter', []);
        $startDate = $filters['startDate'] ?? null;
        $endDate   = $filters['endDate'] ?? null;

        // =====================
        // BASE QUERY (ALL TIME)
        // =====================
        $ordersQuery = Transaction::query();
        $incomeQuery = Transaction::where('status', 'delivered');

        // =====================
        // DATE FILTER (OPTIONAL)
        // =====================
        if ($startDate && $endDate) {
            $ordersQuery->whereBetween('created_at', [$startDate, $endDate]);
            $incomeQuery->whereBetween('created_at', [$startDate, $endDate]);

            $ordersDescription = 'Total order dalam periode';
            $incomeLabel       = 'Income Periode';
            $incomeDescription = 'Dari ' .
                Carbon::parse($startDate)->translatedFormat('d F Y') .
                ' sampai ' .
                Carbon::parse($endDate)->translatedFormat('d F Y');

            $profitRangeLabel = $incomeDescription;
        } else {
            // DEFAULT = SEMUA DATA
            $ordersDescription = 'Total seluruh order';
            $incomeLabel       = 'Total Income';
            $incomeDescription = 'Semua periode';
            $profitRangeLabel  = 'Semua periode';
        }

        // =====================
        // DATA
        // =====================
        $totalProducts = Product::count();
        $totalOrders   = $ordersQuery->count();
        $income        = $incomeQuery->sum('total');

        // =====================
        // PROFIT
        // =====================
        $profitQuery = TransactionItem::join(
            'transactions',
            'transaction_items.transaction_id',
            '=',
            'transactions.id'
        )
            ->join(
                'products',
                'transaction_items.product_id',
                '=',
                'products.id'
            )
            ->where('transactions.status', 'delivered');

        if ($startDate && $endDate) {
            $profitQuery->whereBetween('transactions.created_at', [$startDate, $endDate]);
        }

        $profit = $profitQuery
            ->selectRaw('SUM((products.sell_price - products.purchase_price) * transaction_items.quantity) as total_profit')
            ->value('total_profit') ?? 0;

        // =====================
        // STATS
        // =====================
        return [
            Stat::make('Total Products', $totalProducts)
                ->description('Total Produk Tersedia')
                ->descriptionIcon('heroicon-m-cube')
                ->color('success'),

            Stat::make('Total Orders', $totalOrders)
                ->description($ordersDescription)
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary'),

            Stat::make($incomeLabel, 'Rp ' . number_format($income, 0, ',', '.'))
                ->icon('heroicon-o-banknotes')
                ->color('success')
                ->description($incomeDescription),

            Stat::make('Total Profit', 'Rp ' . number_format($profit, 0, ',', '.'))
                ->description($profitRangeLabel),
        ];
    }
}
