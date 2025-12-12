<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    protected $fillable = [
        'order_code',
        'customer_id',
        'address',
        'province',
        'city',
        'phone_number',
        'district',
        'postal_code',
        'courier',
        'courier_service',
        'shipping_cost',
        'tracking_number',
        'payment_method',
        'status',
        'total',
        'notes',
        'snap_token',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function getCustomerEmailAttribute()
    {
        return $this->customer->email ?? '';
    }
}
