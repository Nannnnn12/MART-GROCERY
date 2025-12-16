<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\DeliveredTransactions;
use App\Filament\Widgets\IncomeStatsOverview;
use App\Filament\Widgets\IncomeStatsOverview2;
use App\Models\Transaction;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Widgets\StatsOverviewWidget\Stat;
use UnitEnum;

class Income extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-currency-dollar';
    protected static string|UnitEnum|null $navigationGroup = 'Manajemen Order';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Pendapatan';

    protected static ?string $title = 'Pendapatan';

    protected string $view = 'filament.pages.income';

    public function getWidgets(): array
    {
        return [
            IncomeStatsOverview::class,
            IncomeStatsOverview2::class,
             DeliveredTransactions::class,
        ];
    }

}
