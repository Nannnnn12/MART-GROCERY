<?php

namespace App\Helpers;

use App\Models\Store;

if (!function_exists('store')) {
    /**
     * Ambil data toko dari database.
     */
    function store()
    {
        return Store::first();
    }
}

if (!function_exists('instagram_link')) {
    function instagram_link()
    {
        $store = store();
        if (!$store || !$store->instagram) return null;

        return str_starts_with($store->instagram, 'http')
            ? $store->instagram
            : 'https://instagram.com/' . ltrim($store->instagram, '@');
    }
}

if (!function_exists('whatsapp_link')) {
    function whatsapp_link()
    {
        $store = store();
        if (!$store || !$store->whatsapp) return null;

        $number = preg_replace('/[^0-9]/', '', $store->whatsapp);
        return 'https://wa.me/' . $number;
    }
}

