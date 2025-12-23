<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatsOverview extends BaseWidget
{
    protected $listeners = ['pendapatan-filter-updated' => '$refresh'];
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $filters   = session('pendapatan_filter', []);
        $startDate = $filters['startDate'] ?? null;
        $endDate   = $filters['endDate'] ?? null;
        $year      = $filters['year'] ?? null;
        $month     = $filters['month'] ?? null;

        // =====================
        // BASE QUERY
        // =====================
        $ordersQuery = Transaction::query();
        $incomeQuery = Transaction::where('status', 'delivered');

        // =====================
        // FILTER
        // =====================
        if ($startDate && $endDate) {
            $ordersQuery->whereBetween('created_at', [$startDate, $endDate]);
            $incomeQuery->whereBetween('created_at', [$startDate, $endDate]);

            $rangeLabel = Carbon::parse($startDate)->translatedFormat('d F Y')
                . ' - ' .
                Carbon::parse($endDate)->translatedFormat('d F Y');

            $ordersDescription = 'Order periode';
            $incomeLabel = 'Income Periode';
            $incomeDescription = $rangeLabel;
            $profitDescription = $rangeLabel;

        } elseif ($year && $month) {
            $ordersQuery->whereYear('created_at', $year)->whereMonth('created_at', $month);
            $incomeQuery->whereYear('created_at', $year)->whereMonth('created_at', $month);

            $date = Carbon::create($year, $month, 1)->translatedFormat('F Y');

            $ordersDescription = 'Order bulan ' . $date;
            $incomeLabel = 'Income Bulanan';
            $incomeDescription = $date;
            $profitDescription = $date;

        } elseif ($year) {
            $ordersQuery->whereYear('created_at', $year);
            $incomeQuery->whereYear('created_at', $year);

            $ordersDescription = 'Order tahun ' . $year;
            $incomeLabel = 'Income Tahunan';
            $incomeDescription = 'Tahun ' . $year;
            $profitDescription = 'Tahun ' . $year;

        } else {
            $ordersDescription = 'Seluruh order';
            $incomeLabel = 'Total Income';
            $incomeDescription = 'Semua periode';
            $profitDescription = 'Semua periode';
        }

        // =====================
        // DATA UTAMA
        // =====================
        $totalProducts = Product::count();
        $totalOrders   = $ordersQuery->count();

        // ✅ TOTAL INCOME = total - shipping_cost
        $income = $incomeQuery
            ->selectRaw('SUM(total - shipping_cost) as income')
            ->value('income') ?? 0;

        // =====================
        // PROFIT (TANPA SHIPPING COST)
        // =====================
        $profitQuery = TransactionItem::query()
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->where('transactions.status', 'delivered');

        // APPLY FILTER SAMA
        if ($startDate && $endDate) {
            $profitQuery->whereBetween('transactions.created_at', [$startDate, $endDate]);
        } elseif ($year && $month) {
            $profitQuery->whereYear('transactions.created_at', $year)
                ->whereMonth('transactions.created_at', $month);
        } elseif ($year) {
            $profitQuery->whereYear('transactions.created_at', $year);
        }

        // ✅ PROFIT MURNI
        $profit = $profitQuery
            ->selectRaw(
                'SUM((products.sell_price - products.purchase_price) * transaction_items.quantity) as profit'
            )
            ->value('profit') ?? 0;

        // =====================
        // STATS
        // =====================
        return [
            Stat::make('Total Products', $totalProducts)
                ->description('Produk tersedia')
                ->color('success')->chart(['25', '30', '75', '20', '75', '50', '25']),

            Stat::make('Total Orders', $totalOrders)
                ->description($ordersDescription)
                ->color('primary')->chart(['10', '30', '50', '70', '90', '70', '50']),

            Stat::make($incomeLabel, 'Rp ' . number_format($income, 0, ',', '.'))
                ->description($incomeDescription)
                ->color('success')->chart(['50', '75', '100', '125', '150', '125', '100']),

            Stat::make('Total Profit', 'Rp ' . number_format($profit, 0, ',', '.'))
                ->description($profitDescription)
                ->color('warning')->chart(['20', '40', '60', '80', '100', '80', '60']),
        ];
    }
}
