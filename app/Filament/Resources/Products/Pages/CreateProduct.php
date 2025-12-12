<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['slug'] = \Illuminate\Support\Str::slug($data['product_name']);

        return $data;
    }

    protected function afterCreate(): void
    {
        $images = $this->form->getState()['images'] ?? [];

        foreach ($images as $image) {
            $this->record->images()->create([
                'image_path' => $image,
            ]);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
