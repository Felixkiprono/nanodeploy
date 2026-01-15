<?php

namespace App\Filament\Resources\SSHKeys\Pages;

use App\Filament\Resources\SSHKeys\SSHKeyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;

class ListSSHKeys extends ListRecords
{
    protected static string $resource = SSHKeyResource::class;

    protected function getHeaderDescription(): ?string
    {
        return 'Manage SSH keys used to access your servers securely.';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Add Existing SSH Key'),
            Action::make('generate')
                ->label('Generate NanoDeploy Key')
                ->icon('heroicon-o-cpu-chip')
                ->url(static::$resource::getUrl('generate'))
                ->color('warning'),
        ];
    }
}
