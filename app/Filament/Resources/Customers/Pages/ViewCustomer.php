<?php

namespace App\Filament\Resources\Customers\Pages;

use App\Filament\Resources\Customers\CustomerResource;
use App\Filament\Widgets\CustomerStatsOverview;
use App\Filament\Widgets\CustomerTransactions;
use App\Filament\Widgets\CustomerReviews;
use Filament\Infolists\Components\StatsOverviewEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class ViewCustomer extends ViewRecord
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\CustomerStatsOverview::make([
            'customerId' => $this->record->id,
        ]),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            CustomerTransactions::make([
                'customerId' => $this->record->id,
            ]),
            CustomerReviews::make([
                'customerId' => $this->record->id,
            ]),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema

            ->schema([
                Section::make('Informasi Pelanggan')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Nama'),
                        TextEntry::make('email')
                            ->label('Email'),
                        TextEntry::make('address')
                            ->label('Alamat'),
                        TextEntry::make('phone_number')
                            ->label('Nomor Telepon'),
                    ])->columnSpan('full')
                    ->columns(2),

            ]);
    }
}
