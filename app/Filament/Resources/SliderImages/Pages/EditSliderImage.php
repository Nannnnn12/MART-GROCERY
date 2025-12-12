<?php

namespace App\Filament\Resources\SliderImages\Pages;

use App\Filament\Resources\SliderImages\SliderImageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSliderImage extends EditRecord
{
    protected static string $resource = SliderImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
