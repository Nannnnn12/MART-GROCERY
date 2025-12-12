<?php

namespace App\Filament\Resources\Transactions\Tables;

use Filament\Actions\BulkActionGroup;
use Illuminate\Support\Str;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Illuminate\Support\Facades\Http;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Carbon;
use Filament\Tables\Filters\DateFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Table;
use App\Models\Transaction;

class TransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('order_code')
                    ->searchable(),
                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable(),
                SelectColumn::make('status')
                    ->options(function ($record) {
                        $options = [
                            'pending' => 'Pending',
                            'processing' => 'Processing',
                            'shipped' => 'Shipped',
                            'delivered' => 'Delivered',
                            'cancelled' => 'Cancelled',
                        ];
                        if ($record->payment_method !== 'cod') {
                            $options['belum_dibayar'] = 'Belum Dibayar';
                        }
                        return $options;
                    })
                    ->rules(['required'])
                    ->selectablePlaceholder(false),
                TextColumn::make('total')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('province')
                    ->label('Provinsi')
                    ->toggleable(),
                TextColumn::make('city')
                    ->label('Kota')
                    ->toggleable(),
                TextColumn::make('district')
                    ->label('Kecamatan')
                    ->toggleable(),
                TextColumn::make('courier')
                    ->label('Kurir')
                    ->badge()
                    ->color('success')
                    ->toggleable(),
                TextColumn::make('courier_service')
                    ->label('Layanan Kurir')
                    ->badge()
                    ->color('primary')
                    ->toggleable(),
                TextColumn::make('shipping_cost')
                    ->label('Biaya Pengiriman')
                    ->money('IDR')
                    ->toggleable(),
                TextColumn::make('tracking_number')
                    ->label('Nomor Resi')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('payment_method')
                    ->label('Metode Pembayaran')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'midtrans' => 'info',
                        default => 'warning',
                    }),
            ])
            ->filters([
                SelectFilter::make('year')
                    ->label('Year')
                    ->options(function () {
                        return Transaction::selectRaw('YEAR(created_at) as year')
                            ->distinct()
                            ->orderBy('year', 'desc')
                            ->pluck('year', 'year')
                            ->toArray();
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'],
                            fn (Builder $query, $year): Builder => $query->whereYear('created_at', $year),
                        );
                    }),
                SelectFilter::make('month')
                    ->label('Month')
                    ->options([
                        1 => 'January',
                        2 => 'February',
                        3 => 'March',
                        4 => 'April',
                        5 => 'May',
                        6 => 'June',
                        7 => 'July',
                        8 => 'August',
                        9 => 'September',
                        10 => 'October',
                        11 => 'November',
                        12 => 'December',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'],
                            fn (Builder $query, $month): Builder => $query->whereMonth('created_at', $month),
                        );
                    }),
                SelectFilter::make('day')
                    ->label('Day')
                    ->options(array_combine(range(1, 31), range(1, 31)))
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'],
                            fn (Builder $query, $day): Builder => $query->whereDay('created_at', $day),
                        );
                    }),

            ])
            ->recordActions([
                EditAction::make()
                    ->label('Lihat')
                    ->icon('heroicon-o-eye'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
