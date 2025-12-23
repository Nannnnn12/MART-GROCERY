<?php

namespace App\Filament\Resources\Incomes\Tables;

use App\Models\Transaction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class IncomesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(function () {
                $query = Transaction::query()->where('status', 'delivered');
                $filter = session('pendapatan_filter', []);
                if (!empty($filter['startDate'])) {
                    $query->whereDate('created_at', '>=', $filter['startDate']);
                }
                if (!empty($filter['endDate'])) {
                    $query->whereDate('created_at', '<=', $filter['endDate']);
                }
                return $query->latest();
            })
            ->columns([
                TextColumn::make('order_code')
                    ->label('Order Code')
                    ->searchable(),
                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable(),
                TextColumn::make('total')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated(10);
    }
}
