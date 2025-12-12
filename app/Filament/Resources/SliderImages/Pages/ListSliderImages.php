<?php

namespace App\Filament\Resources\SliderImages\Pages;

use App\Filament\Resources\SliderImages\SliderImageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSliderImages extends ListRecords
{
    protected static string $resource = SliderImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
