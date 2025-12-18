<?php

namespace App\Filament\Resources\News\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class NewsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                TextInput::make('slug')
                    ->required()
                    ->disabled()
                    ->dehydrated(),
                TextInput::make('category')
                    ->default(null),
                TextInput::make('source')
                    ->required()
                    ->default('Antara'),
                TextInput::make('source_url')
                    ->url()
                    ->required()
                    ->disabled()
                    ->dehydrated(),
                TextInput::make('guid')
                    ->default(null)
                    ->disabled()
                    ->dehydrated(),
                MarkdownEditor::make('excerpt')
                    ->default(null)
                    ->columnSpanFull(),
                MarkdownEditor::make('content')
                    ->default(null)
                    ->columnSpanFull()
                    ->disabled()
                    ->dehydrated(false),
                FileUpload::make('image_url')
                    ->image()
                    ->disabled()
                    ->dehydrated(),
                DateTimePicker::make('published_at')
                    ->disabled()
                    ->dehydrated(),
                Toggle::make('is_published')
                    ->required(),
            ]);
    }
}
