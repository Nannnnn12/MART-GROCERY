<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FonnteService
{
    protected $token;

    public function __construct()
    {
        $this->token = config('services.fonnte.token');
    }

    public function sendMessage(string $phone, string $message, ?string $image = null)
    {
        // Format phone number to international format +62xxxxxxxxx
        if (str_starts_with($phone, '+62')) {
            // Already in correct format
        } elseif (str_starts_with($phone, '62')) {
            $phone = '+' . $phone;
        } elseif (str_starts_with($phone, '0')) {
            $phone = '+62' . substr($phone, 1);
        } else {
            // Assume it's local number without prefix, add +62
            $phone = '+62' . $phone;
        }

        $data = [
            'target' => $phone,
            'message' => $message,
        ];

        if ($image) {
            $imageUrl = asset('storage/' . $image);
            \Log::info('Fonnte image URL: ' . $imageUrl);
            $data['image'] = $imageUrl;
        }

        $response = Http::withHeaders([
            'Authorization' => $this->token
        ])->post('https://api.fonnte.com/send', $data);

        return $response->json();
    }
}
