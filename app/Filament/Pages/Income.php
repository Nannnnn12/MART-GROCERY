<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\IncomeStatsOverview;
use App\Models\Transaction;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Widgets\StatsOverviewWidget\Stat;
use UnitEnum;

class Income extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-currency-dollar';
    protected static string|UnitEnum|null $navigationGroup = 'Laporan';
    protected static ?int $navigationSort = 5;
    protected static ?string $navigationLabel = 'Pendapatan';

    protected static ?string $title = 'Pendapatan';

    protected string $view = 'filament.pages.income';

    public function getWidgets(): array
    {
        return [
            IncomeStatsOverview::class,
             \App\Filament\Widgets\IncomeChart::class,
        ];
    }

}
