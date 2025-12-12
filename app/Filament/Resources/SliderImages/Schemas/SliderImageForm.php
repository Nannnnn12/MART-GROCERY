<?php

namespace App\Filament\Resources\SliderImages\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SliderImageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->default(null),
                FileUpload::make('image_path')
                    ->image()
                    ->disk('public')
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
