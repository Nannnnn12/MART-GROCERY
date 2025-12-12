<?php

namespace App\Filament\Resources\SliderImages\Pages;

use App\Filament\Resources\SliderImages\SliderImageResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSliderImage extends CreateRecord
{
    protected static string $resource = SliderImageResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
