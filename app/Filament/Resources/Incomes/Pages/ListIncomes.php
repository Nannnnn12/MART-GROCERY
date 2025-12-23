<?php

namespace App\Filament\Resources\Incomes\Pages;

use App\Filament\Resources\Incomes\IncomeResource;
use App\Filament\Widgets\IncomeStatsOverview;
use App\Filament\Widgets\IncomeFiltersWidget;
use Filament\Actions\CreateAction;
use Filament\Schemas\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
class ListIncomes extends ListRecords
{

    protected static string $resource = IncomeResource::class;

    protected $listeners = ['pendapatan-filter-updated' => '$refresh'];

    protected function getHeaderActions(): array
    {
        return [
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            IncomeFiltersWidget::class,
            IncomeStatsOverview::class,
        ];
    }

}

