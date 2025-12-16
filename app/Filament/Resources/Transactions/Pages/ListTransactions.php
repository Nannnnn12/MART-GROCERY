<?php

namespace App\Filament\Resources\Transactions\Pages;

use App\Filament\Resources\Transactions\TransactionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
           
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua'),
            'pending' => Tab::make('Menunggu')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'pending')),
            'processing' => Tab::make('Diproses')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'processing')),
            'shipped' => Tab::make('Dikirim')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'shipped')),
            'delivered' => Tab::make('Diterima')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'delivered')),
            'cancelled' => Tab::make('Dibatalkan')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'cancelled')),
            'belum_dibayar' => Tab::make('Belum Dibayar')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'belum_dibayar')),
        ];
    }
}
