<?php

namespace App\Filament\Resources\SSHKeys\Pages;

use App\Filament\Resources\SSHKeys\SSHKeyResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Hidden;

class CreateSSHKey extends CreateRecord
{
    protected static string $resource = SSHKeyResource::class;

    protected function getCreateButtonLabel(): string
    {
        return 'Add SSH Key';
    }

    public function canCreateAnother(): bool
    {
        return false;
    }

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('SSH Key Details')
                ->description('Add an existing public SSH key to use for server access.')
                ->icon('heroicon-o-key')
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->helperText(
                            'A friendly name to help you recognize this key later, e.g. "MacBook Pro" or "CI Runner".'
                        ),

                    Hidden::make('type')
                        ->default('user'),

                    Textarea::make('public_key')
                        ->required()
                        ->rows(4)
                        ->helperText(
                            'Paste the public SSH key. This is safe to share and usually ends with .pub.'
                        ),
                ]),
        ]);
    }
}
