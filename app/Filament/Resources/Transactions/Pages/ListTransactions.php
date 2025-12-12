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
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All'),
            'pending' => Tab::make('Pending')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'pending')),
            'processing' => Tab::make('Processing')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'processing')),
            'shipped' => Tab::make('Shipped')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'shipped')),
            'delivered' => Tab::make('Delivered')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'delivered')),
            'cancelled' => Tab::make('Cancelled')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'cancelled')),
            'belum_dibayar' => Tab::make('Belum Dibayar')
                ->modifyQueryUsing(fn ($query) => $query->where('status', 'belum_dibayar')),
        ];
    }
}
