<?php

namespace App\Filament\Resources\Customers\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\viewAction;

class CustomersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),

                TextColumn::make('transactions_count')
                    ->label('Jumlah Pesanan')
                    ->counts('transactions'),

                TextColumn::make('reviews_count')
                    ->label('Jumlah Review')
                    ->counts('reviews'),

            ])
            ->actions([
                ViewAction::make()
                    ->label('Lihat'),
            ])
            ->bulkActions([
                // No bulk actions
            ]);
    }
}
