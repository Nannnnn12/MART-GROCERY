<?php

namespace App\Filament\Resources\Incomes;

use App\Filament\Resources\Incomes\Pages\CreateIncome;
use App\Filament\Resources\Incomes\Pages\EditIncome;
use App\Filament\Resources\Incomes\Pages\ListIncomes;
use App\Filament\Resources\Incomes\Schemas\IncomeForm;
use App\Filament\Resources\Incomes\Tables\IncomesTable;
use App\Models\Income;
use BackedEnum;
use Filament\Resources\Concerns\HasFiltersForm;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class IncomeResource extends Resource
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-currency-dollar';
    protected static string|UnitEnum|null $navigationGroup = 'Manajemen Order';
    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Pendapatan';

    protected static ?string $pluralModelLabel = 'Pendapatan';

    protected static ?string $navigationLabel = 'Pendapatan';


    public static function form(Schema $schema): Schema
    {
        return IncomeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return IncomesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListIncomes::route('/'),
            'create' => CreateIncome::route('/create'),
            'edit' => EditIncome::route('/{record}/edit'),
        ];
    }
}
