<?php

namespace App\Filament\Resources\Servers\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;

class ServerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('name')
                ->required(),

            TextInput::make('host')
                ->required()
                ->label('Server Host / IP'),

            TextInput::make('port')
                ->numeric()
                ->default(22),

            TextInput::make('username')
                ->required(),

            TextInput::make('ssh_key_path')
                ->label('SSH Key Path')
                ->helperText('Optional for now'),
        ]);
    }
}
