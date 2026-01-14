<?php

namespace App\Filament\Resources\Servers\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;

class ServerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Server Information')
                    ->description('Basic connection details for your server')
                    ->icon('heroicon-o-server-stack')
                    ->columnSpan('full')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->placeholder('Production Server')
                            ->maxLength(255),

                        TextInput::make('host')
                            ->required()
                            ->placeholder('192.168.1.10 or example.com')
                            ->label('Server Host / IP'),

                        TextInput::make('port')
                            ->numeric()
                            ->default(22)
                            ->minValue(1)
                            ->maxValue(65535),

                        TextInput::make('username')
                            ->required()
                            ->placeholder('root'),

                        TextInput::make('ssh_key_path')
                            ->label('SSH Key Path')
                            ->placeholder('~/.ssh/id_rsa')
                            ->helperText('Optional — used for manual connections'),
                    ]),

                Section::make('Provisioning')
                    ->description('Optional server provisioning options')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->columnSpan('full')
                    ->collapsed() // 👈 collapsed by default
                    ->schema([
                        Toggle::make('auto_provision')
                            ->label('Provision this server automatically')
                            ->helperText('Install required services and harden security'),

                        Select::make('os')
                            ->label('Operating System')
                            ->options([
                                'ubuntu_22_04' => 'Ubuntu 22.04 LTS',
                                'ubuntu_20_04' => 'Ubuntu 20.04 LTS',
                            ])
                            ->visible(fn ($get) => $get('auto_provision'))
                            ->default('ubuntu_22_04'),
                    ]),
            ]);
    }
}
