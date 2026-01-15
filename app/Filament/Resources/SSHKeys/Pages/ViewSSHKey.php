<?php

namespace App\Filament\Resources\SSHKeys\Pages;

use App\Filament\Resources\SSHKeys\SSHKeyResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;

class ViewSSHKey extends ViewRecord
{
    protected static string $resource = SSHKeyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Key Details')
                ->columns(2)
                ->schema([
                    Text::make('name'),
                    Text::make('type'),
                    Text::make('fingerprint'),
                    Text::make('created_at'),
                ]),

            Section::make('Public Key')
                ->schema([
                    Text::make('public_key')
                        ->copyable(),
                ]),

            Section::make('Private Key')
                ->description('Private keys are only stored for NanoDeploy-generated keys.')
                ->schema([
                    Text::make('private_key'),
                ]),
        ]);
    }
}
