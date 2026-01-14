<?php

namespace App\Filament\Resources\Servers\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;

class ServersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Server')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->host)
                    ->icon('heroicon-o-server-stack'),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'gray' => 'pending',
                        'warning' => 'connecting',
                        'info' => 'provisioning',
                        'success' => 'ready',
                        'danger' => 'failed',
                    ])
                    ->icons([
                        'heroicon-o-clock' => 'pending',
                        'heroicon-o-arrow-path' => 'connecting',
                        'heroicon-o-cog-6-tooth' => 'provisioning',
                        'heroicon-o-check-circle' => 'ready',
                        'heroicon-o-x-circle' => 'failed',
                    ])
                    ->sortable(),

                TextColumn::make('sites_count')
                    ->label('Sites')
                    ->counts('sites')
                    ->alignCenter()
                    ->badge()
                    ->color('primary'),

                TextColumn::make('username')
                    ->label('User')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Added')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            ->recordActions([
                Action::make('manage')
                    ->label('Manage')
                    ->icon('heroicon-o-command-line')
                    ->color('primary')
                    ->url(fn ($record) =>
                        route('filament.panel.resources.servers.edit', $record)
                    ),
            ])->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50]);
    }
}
