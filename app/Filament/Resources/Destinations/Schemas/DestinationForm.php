<?php

namespace App\Filament\Resources\Destinations\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TagsInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Str;


class DestinationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Utama')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('name')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn($state, callable $set) => $set('slug', Str::slug($state))),
                        TextInput::make('slug')
                            ->disabled()
                            ->dehydrated()
                            ->required(),
                    ]),
                    Grid::make(2)->schema([
                        TextInput::make('category'),
                        TextInput::make('type')->default('destination'),
                    ]),
                    Grid::make(2)->schema([
                        TextInput::make('title'),
                        TextInput::make('subtitle'),
                    ]),
                    Textarea::make('deskripsi')
                        ->rows(6)
                        ->columnSpanFull(),
                ]),
            Section::make('Lokasi & Kontak')
                ->schema([
                    TextInput::make('address')->columnSpanFull(),
                    Grid::make(2)->schema([
                        TextInput::make('latitude')->numeric(),
                        TextInput::make('longitude')->numeric(),
                    ]),
                    TextInput::make('contact'),
                    Toggle::make('is_rekomend')->label('Recommended'),
                ]),
            Section::make('Media')
                ->schema([
                    FileUpload::make('gallery')
                        ->image()
                        ->disk('public')
                        ->directory('destinations')
                        ->visibility('public')
                        ->multiple()
                        ->maxFiles(10)
                        ->reorderable()
                        ->panelLayout('grid'),
                ]),
            Section::make('Tambahan')
                ->schema([
                    KeyValue::make('opening_hours')
                        ->keyLabel('Hari')
                        ->valueLabel('Jam buka')
                        ->addActionLabel('Tambah hari')
                        ->columnSpanFull(),
                    TagsInput::make('tags')->placeholder('Tambah tag...'),
                ]),
        ]);
    }
}
