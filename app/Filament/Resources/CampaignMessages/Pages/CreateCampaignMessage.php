<?php

namespace App\Filament\Resources\CampaignMessages\Pages;

use App\Filament\Resources\CampaignMessages\CampaignMessageResource;
use App\Models\CampaignMessage;
use App\Models\Customer;
use App\Models\User;
use App\Services\FonnteService;
use Filament\Resources\Pages\CreateRecord;

class CreateCampaignMessage extends CreateRecord
{
    protected static string $resource = CampaignMessageResource::class;

    protected function handleRecordCreation(array $data): CampaignMessage
    {
        return new CampaignMessage();
    }

    protected function afterCreate(): void
    {
        $data = $this->form->getState();

        // ambil customers sesuai mode
        if ($data['mode'] === 'all') {
            $customers = User::where('is_active', true)->get();
        } else {
            $customers = User::whereIn('id', $data['customer_ids'])->get();
        }

        $fonnte = app(FonnteService::class);

        foreach ($customers as $customer) {
            // Skip if phone number is null or empty
            if (empty($customer->phone_number)) {
                continue;
            }

            // buat record history
            $history = CampaignMessage::create([
                'customer_id' => $customer->id,
                'title'       => $data['title'],
                'message'     => $data['message'],
                'image'       => $data['image'] ?? null,
                'status'      => 'pending',
            ]);

            // kirim wa via fonnte
            $response = $fonnte->sendMessage($customer->phone_number, $data['message'], $data['image'] ?? null);

            $history->update([
                'status'          => ($response['status'] ?? false) ? 'sent' : 'failed',
                'fonnte_response' => json_encode($response),
                'sent_at'         => now(),
            ]);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
