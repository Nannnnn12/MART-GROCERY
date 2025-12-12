<?php

namespace App\Filament\Widgets;

use App\Models\Review;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class CustomerReviews extends BaseWidget
{
    protected static bool $isDiscovered = false;

    public ?int $customerId = null;

    public function mount(?int $customerId = null): void
    {
        $this->customerId = $customerId;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Review::query()->where('user_id', $this->customerId)->latest()
            )
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Product')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rating')
                    ->label('Rating'),
                Tables\Columns\TextColumn::make('comment')
                    ->label('Comment'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated(10);
    }
}
