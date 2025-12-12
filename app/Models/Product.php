<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'product_name',
        'slug',
        'description',
        'purchase_price',
        'sell_price',
        'stock',
        'status',
        'weight',
        'category_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->product_name);
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('product_name') && empty($product->slug)) {
                $product->slug = Str::slug($product->product_name);
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function orders()
    {
        return $this->hasManyThrough(Transaction::class, TransactionItem::class, 'product_id', 'id', 'id', 'transaction_id');
    }

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }
}
