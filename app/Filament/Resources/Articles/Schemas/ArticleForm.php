<?php

namespace App\Filament\Resources\Articles\Schemas;

use App\Models\ArticleCategory;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Judul')
                    ->required()
                    ->columnSpanFull(),

                RichEditor::make('content')
                    ->label('Konten')
                    ->required()
                    ->columnSpanFull(),



                Select::make('category_id')
                    ->label('Kategori')
                    ->options(ArticleCategory::pluck('name', 'id'))
                    ->nullable()
                    ->columnSpan(1),

                FileUpload::make('image')
                    ->label('Thumbnail')
                    ->image()
                    ->disk('public')
                    ->directory('articles')
                    ->imageEditor()
                    ->columnSpan(1),

                Toggle::make('is_published')
                    ->label('Dipublikasikan')
                    ->columnSpan(1),
            ])
            ->columns(2);
    }
}
