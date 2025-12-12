<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Category;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('product_name')
                ->label('Nama Produk')
                    ->required()
                    ->afterStateUpdated(function (Set $set, ?string $state) {
                        $set('slug', Str::slug($state));
                    }),
                Select::make('category_id')
                    ->label('Kategori')
                    ->options(Category::all()->pluck('category_name', 'id'))
                    ->required(),
                RichEditor::make('description')
                    ->label('Deskripsi')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('purchase_price')
                ->label('Harga Beli')
                    ->required()
                    ->numeric(),
                TextInput::make('sell_price')
                ->label('Harga Jual')
                    ->required()
                    ->numeric(),
                TextInput::make('weight')
                ->label('Berat (gram)')
                    ->numeric(),
                TextInput::make('stock')
                ->label('Stok')
                    ->required()
                    ->numeric(),
                Select::make('status')
                ->label('Status')
                    ->options(['active' => 'Active', 'inactive' => 'Inactive'])
                    ->default('active')
                    ->required(),
                FileUpload::make('images')
                    ->label('Gambar Produk')
                    ->image()
                    ->multiple()
                    ->disk('public')
                    ->directory('product-images')
                    ->visibility('public')
                    ->columnSpanFull(),
            ]);
    }
}
