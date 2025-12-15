<?php

namespace App\Filament\Resources\AppUsers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AppUserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->default(null),
                TextInput::make('provider')
                    ->default(null),
                TextInput::make('provider_id')
                    ->default(null),
                TextInput::make('role')
                    ->required()
                    ->default('user'),
            ]);
    }
}
