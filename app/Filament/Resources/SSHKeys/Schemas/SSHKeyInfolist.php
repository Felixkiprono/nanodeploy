<?php

namespace App\Filament\Resources\SSHKeys\Schemas;

use App\Models\SSHKey;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SSHKeyInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('type'),
                TextEntry::make('public_key')
                    ->columnSpanFull(),
                TextEntry::make('private_key')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('fingerprint')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (SSHKey $record): bool => $record->trashed()),
            ]);
    }
}
