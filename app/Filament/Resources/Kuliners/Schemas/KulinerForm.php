<?php

namespace App\Filament\Resources\Kuliners\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TimePicker;

class KulinerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Kuliner')
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
                                ->label('Jenis Kuliner')
                                ->options([
                                    'street food' => 'Street Food',
                                    'cafe' => 'Kafe',
                                    'restaurant' => 'Restoran',
                                    'oleh oleh' => 'Oleh-oleh',
                                ])
                                ->required()
                                ->searchable(),
                            TextInput::make('price_range')
                                ->label('Rentang Harga')
                                ->prefix('Rp ')
                                ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 0)
                                ->placeholder('Rp 10.000 - Rp 50.000'),
                        ]),
                        Textarea::make('description')->rows(4)->columnSpanFull(),
                    ]),

                Section::make('Lokasi & Kontak')
                    ->schema([
                        TextInput::make('address')->columnSpanFull(),
                        Grid::make(2)->schema([
                            TextInput::make('latitude')->numeric(),
                            TextInput::make('longitude')->numeric(),
                        ]),
                        TextInput::make('contact_number')->label('Contact'),
                        Toggle::make('is_featured')->label('Rekomendasi'),
                    ]),
                Section::make('Media')
                    ->schema([
                        FileUpload::make('gallery')
                            ->image()
                            ->disk('public')
                            ->directory('kuliner')
                            ->multiple()
                            ->maxfiles(10)
                            ->reorderable()
                            ->visibility('public'),
                    ]),
                Section::make('Informasi Tambahan')
                    ->schema([
                        Repeater::make('opening_hours')
                            ->schema([
                                Select::make('day')
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
                                TimePicker::make('opens_at')->seconds(),
                                TimePicker::make('closes_at')->seconds(),
                            ])
                            ->columns(3)
                            ->columnSpanFull(),
                        Repeater::make('sociail_links')
                            ->schema([
                                Select::make('platform')->options([
                                    'website' => 'Website',
                                    'instagram' => 'Instagram',
                                    'facebook' => 'Facebook',
                                    'tiktok' => 'Tiktok',
                                    'youtube' => 'Youtube',
                                ]),
                                TextInput::make('url')->url(),
                            ])
                            ->columns(2)
                            ->columnSpanFull(),
                        // TagsInput::make('tags')->placeholder('Tambah tag')
                    ])
            ]);
    }
}
