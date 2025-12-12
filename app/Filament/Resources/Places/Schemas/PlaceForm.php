<?php

namespace App\Filament\Resources\Places\Schemas;

use Filament\Support\Components\TextInput\Masks\Mask;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PlaceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
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
                            Select::make('category')
                                ->label('Kategori')
                                ->options([
                                    'wisata_alam' => 'Wisata Alam',
                                    'wisata_budaya' => 'Wisata Budaya',
                                    'wisata_religi' => 'Wisata Religi',
                                    'wisata_keluarga' => 'Wisata Keluarga',
                                ])
                                ->searchable()
                                ->required()
                        ]),
                        TextInput::make('ticket_price')
                            ->label('Harga Tiket')
                            ->prefix('Rp ')
                            ->currencyMask(
                                thousandSeparator: '.',
                                decimalSeparator: ',',
                                precision: 0
                            ),
                        Textarea::make('description')->rows(4)->columnSpanFull(),
                    ]),
                Section::make('Lokasi & Kontak')
                    ->schema([
                        TextInput::make('address')->columnSpanFull(),
                        Grid::make(2)->schema([
                            TextInput::make('latitude')->numeric(),
                            TextInput::make('longitude')->numeric(),
                        ]),
                        TextInput::make('contact'),
                        Toggle::make('is_featured')->label('Recommended'),
                    ]),
                Section::make('Media')
                    ->schema([
                        FileUpload::make('gallery')
                            ->image()
                            ->disk('public')
                            ->directory('places')
                            ->multiple()
                            ->maxFiles(10)
                            ->reorderable()
                            ->panelLayout('grid')
                            ->visibility('public'),
                    ]),
                Section::make('Informasi Pendukung')
                    ->schema([
                        Repeater::make('opening_hours')
                            ->label('Opening Hours')
                            ->schema([
                                Select::make('day')
                                    ->required()
                                    ->options([
                                        'monday' => 'Senin',
                                        'tuesday' => 'Selasa',
                                        'wednesday' => 'Rabu',
                                        'thursday' => 'Kamis',
                                        'friday' => 'Jumat',
                                        'saturday' => 'Sabtu',
                                        'sunday' => 'Minggu',
                                        'everyday' => 'Setiap Hari'
                                    ]),
                                TimePicker::make('opens_at')
                                    ->label('Buka')
                                    ->seconds(false)
                                    ->required(),
                                TimePicker::make('close_at')
                                    ->label('Tutup')
                                    ->seconds(false)
                                    ->required(),
                            ])
                            ->columns(3)
                            ->columnSpanFull(),
                        // TagsInput::make('tags'),
                        Repeater::make('sosial_links')
                            ->label('Sosial Links')
                            ->schema([
                                Select::make('platform')
                                    // ->required()
                                    ->options([
                                        'website' => 'Website',
                                        'instagram' => 'Instagram',
                                        'facebook' => 'Facebook',
                                        'tiktok' => 'Tiktok',
                                        'youtube' => 'Youtube',
                                    ]),
                                TextInput::make('url')
                                    // ->required()
                                    ->url()
                                    ->placeholder('https://...'),
                            ])
                            ->columns(2)
                            ->columnSpanFull()
                    ]),
            ]);
    }
}
