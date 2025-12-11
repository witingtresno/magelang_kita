<?php

namespace App\Filament\Resources\Destinations;

use App\Filament\Resources\Destinations\Pages\CreateDestination;
use App\Filament\Resources\Destinations\Pages\EditDestination;
use App\Filament\Resources\Destinations\Pages\ListDestinations;
use App\Filament\Resources\Destinations\Schemas\DestinationForm;
use App\Models\Destination;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;


class DestinationResource extends Resource
{
    protected static ?string $model = Destination::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return DestinationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('category')->badge(),
                IconColumn::make('is_rekomend')
                    ->boolean()
                    ->label('Recommended'),
                TextColumn::make('address')->limit(30),
                TextColumn::make('created_at')->date()->sortable(),
            ])
            ->filters([
                //
            ]);

        // ->actions([
        //     EditAction::make(),
        // ])
        // ->bulkActions([
        //     DeleteBulkAction::make(),
        // ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDestinations::route('/'),
            'create' => CreateDestination::route('/create'),
            'edit' => EditDestination::route('/{record}/edit'),
        ];
    }
}
