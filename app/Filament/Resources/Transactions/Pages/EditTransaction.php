<?php

namespace App\Filament\Resources\Transactions\Pages;

use App\Filament\Resources\Transactions\TransactionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\Action;

class EditTransaction extends EditRecord
{
    protected static string $resource = TransactionResource::class;
    public function getBreadcrumb(): string
    {
        return 'Lihat';
    }

    protected function getHeaderActions(): array
    {
        $actions = [];

        // Add WhatsApp contact button if phone number exists
        if ($this->record->phone_number) {
            $phone = $this->record->phone_number;
            if (substr($phone, 0, 1) === '0') {
                $phone = '+62' . substr($phone, 1);
            }
            $actions[] = Action::make('contact_customer')
                ->label('Hubungi Customer via WA')
                ->icon('heroicon-o-chat-bubble-left-right')
                ->color('success')
                ->url('https://wa.me/' . $phone)
                ->openUrlInNewTab();
        }

        $validStatuses = ['pending', 'processing', 'shipped'];
        if ($this->record->payment_method !== 'cod') {
            $validStatuses[] = 'belum_dibayar';
        }

        if (in_array($this->record->status, $validStatuses)) {
            $actions[] = Action::make('update_status')
                ->label(fn () => match ($this->record->status) {
                    'pending' => 'Proses',
                    'belum_dibayar' => 'Bayar',
                    'processing' => 'Kirim',
                    'shipped' => 'Selesai',
                    default => 'Update',
                })
                ->icon(fn () => match ($this->record->status) {
                    'pending' => 'heroicon-o-cog',
                    'belum_dibayar' => 'heroicon-o-credit-card',
                    'processing' => 'heroicon-o-truck',
                    'shipped' => 'heroicon-o-check-circle',
                    default => 'heroicon-o-arrow-path',
                })
                ->color('primary')
                ->action(function () {
                    $newStatus = match ($this->record->status) {
                        'pending' => 'processing',
                        'belum_dibayar' => 'pending',
                        'processing' => 'shipped',
                        'shipped' => 'delivered',
                        default => $this->record->status,
                    };
                    $this->record->update(['status' => $newStatus]);
                    $this->redirect($this->getResource()::getUrl('index'));
                });
        }

        return $actions;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
