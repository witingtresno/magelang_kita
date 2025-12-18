<?php

namespace App\Filament\Resources\News\Tables;

use App\Models\News;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class NewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('category')
                    ->badge()
                    ->sortable(),
                TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Category')
                    ->options(fn() => News::query()->distinct('category')->pluck('category', 'category')->filter()->toArray()),
                SelectFilter::make('is_published')
                    ->label('Status')
                    ->options([
                        1 => 'Published',
                        0 => 'Draft',
                    ]),
            ]);
    }
}
