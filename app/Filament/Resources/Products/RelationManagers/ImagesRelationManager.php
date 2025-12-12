<?php

namespace App\Filament\Resources\Products\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;
use Filament\Actions\CreateAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Actions;

class ImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'images';

    public function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->components([
                FileUpload::make('image_path')
                    ->label('Gambar Produk')
                    ->image()
                    ->disk('public')
                    ->directory('product-images')
                    ->visibility('public')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Gambar')
                    ->square(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->modalHeading('Tambah Gambar')
                    ->modalButton('Upload')
                    ->createAnother(false),
            ])
            ->actions([
                DeleteAction::make(),
            ])
            ->paginated(false);
    }
}

