<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable = [
        'store_name',
        'description',
        'address',
        'logo',
        'midtrans_client_key',
        'midtrans_server_key',
        'is_production',
        'rajaongkir_api_key',
        'whatsapp',
        'tiktok',
        'facebook',
        'instagram',
        'toko_pedia',
        'shopee',
    ];
}
