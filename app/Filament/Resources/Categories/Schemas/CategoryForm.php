<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Models\Category;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('category_name')
                    ->label('Nama Kategori')
                    ->required()
                    ->afterStateUpdated(function (Set $set, ?string $state) {
                        $set('slug', Str::slug($state));
                    }),
                FileUpload::make('icon')
                    ->label('Ikon')
                    ->image()
                    ->disk('public')
                    ->directory('icon')  // Store uploaded icons in the 'icon' folder inside storage/app/public
                    ->default(null),
            ]);
    }
}
