<?php

namespace App\Filament\Resources\SSHKeys\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;

class SSHKeysTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Key Name')
                    ->icon('heroicon-o-key')
                    ->searchable()
                    ->sortable()
                    ->description(
                        fn($record) =>
                        $record->type === 'system'
                            ? 'Used internally by NanoDeploy'
                            : 'User-provided SSH access key'
                    ),
                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->size('lg')
                    ->color(
                        fn(string $state) =>
                        $state === 'system' ? 'warning' : 'primary'
                    )
                    ->icon(
                        fn(string $state) =>
                        $state === 'system'
                            ? 'heroicon-o-cpu-chip'
                            : 'heroicon-o-user'
                    )
                    ->formatStateUsing(
                        fn(string $state) =>
                        $state === 'system' ? 'NanoDeploy System' : 'User Key'
                    )
                    ->sortable(),
                TextColumn::make('fingerprint')
                    ->label('Fingerprint')
                    ->fontFamily('mono')
                    ->copyable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->icon('heroicon-o-clock')
                    ->dateTime()
                    ->sortable(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->icon('heroicon-o-eye'),
                EditAction::make()
                    ->icon('heroicon-o-pencil'),
                Action::make('revoke')
                    ->label('Revoke')
                    ->icon('heroicon-o-no-symbol')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn($record) => $record->delete()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DeleteBulkAction::make()
                    //     ->label('Revoke selected keys'),
                ]),
            ]);
    }
}
