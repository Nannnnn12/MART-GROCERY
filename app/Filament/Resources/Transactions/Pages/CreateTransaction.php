<?php

namespace App\Filament\Resources\Transactions\Pages;

use App\Filament\Resources\Transactions\TransactionResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;

    protected function afterCreate(): void
    {
        $transaction = $this->record;
        $total = $transaction->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        $transaction->update(['total' => $total]);

        Notification::make()
            ->title('Transaction created successfully')
            ->body('The total has been calculated and updated.')
            ->success()
            ->send();
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
