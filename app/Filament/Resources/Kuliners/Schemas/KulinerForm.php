<?php

namespace App\Filament\Resources\Kuliners\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class KulinerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Kuliner')
                    ->schema([
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
                            'street_food' => 'Street Food',
                            'cafe' => 'Kafe',
                            'restaurant' => 'Restoran',
                            'oleh_oleh' => 'Oleh-oleh',
                        ])
                        ->required()
                        ->searchable(),

                    TextInput::make('price_range')
                        ->label('Rentang Harga')
                        ->prefix('Rp ')
                        ->currencyMask(
                            thousandSeparator: '.',
                            decimalSeparator: ',',
                            precision: 0,
                        )
                        ->placeholder('Rp 10.000 - Rp 50.000'),
                    Textarea::make('description')->rows(4)->columnSpanFull(),
                ]),
                Section::make('Lokasi & Kontak')
                    ->schema([
                        TextInput::make('address')->columnSpanFull(),
                        Grid::make(2)->schema([
                            TextInput::make('latitude')->numeric(),
                            TextInput::make('longitude')->numeric(),
                        ])
                    ])
            ]);
    }
}
