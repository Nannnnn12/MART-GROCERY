<?php

namespace App\Filament\Resources\Transactions\Schemas;

use Dom\Text;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class TransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('order_code')
                    ->required()
                    ->disabled(),
                Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->required()
                    ->disabled(),
                select::make('customer_id')
                    ->relationship('customer', 'email')
                    ->label('Email')
                    ->disabled(),
                TextInput::make('phone_number')
                    ->label('Nomor whatsApp')
                    ->disabled(),
                Textarea::make('address')
                    ->label('Alamat Pengiriman')
                    ->required()
                    ->disabled()
                    ->columnSpanFull(),
                TextInput::make('province')
                    ->label('Provinsi')
                    ->disabled(),
                TextInput::make('city')
                    ->label('Kota')
                    ->disabled(),
                TextInput::make('district')
                    ->label('Kecamatan')
                    ->disabled(),
                TextInput::make('courier')
                    ->label('Kurir')
                    ->disabled(),
                TextInput::make('courier_service')
                    ->label('Layanan Kurir')
                    ->disabled(),
                TextInput::make('tracking_number')
                    ->label('Nomor Resi')
                    ->placeholder('Masukkan nomor resi pengiriman'),
                Select::make('status')
                    ->options(function ($record) {
                        $options = [
                            'pending' => 'Pending',
                            'processing' => 'Processing',
                            'shipped' => 'Shipped',
                            'delivered' => 'Delivered',
                            'cancelled' => 'Cancelled',
                        ];
                        if ($record && $record->payment_method !== 'cod') {
                            $options['belum_dibayar'] = 'Belum Dibayar';
                        }
                        return $options;
                    })
                    ->default('pending')
                    ->required(),
                TextInput::make('total')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->disabled(),
                Textarea::make('notes')
                    ->label('Catatan')
                    ->default(null)
                    ->disabled()
                    ->columnSpanFull(),
                Select::make('payment_method')
                    ->label('Metode Pembayaran')
                    ->options(['cod' => 'Bayar di Tempat (COD)'])
                    ->default('cod')
                    ->required()
                    ->disabled(),
            ]);
    }
}
