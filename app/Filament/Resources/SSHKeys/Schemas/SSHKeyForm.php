<?php

namespace App\Filament\Resources\SSHKeys\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SSHKeyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('type')
                    ->required(),
                Textarea::make('public_key')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('private_key')
                    ->columnSpanFull(),
                TextInput::make('fingerprint'),
            ]);
    }
}
