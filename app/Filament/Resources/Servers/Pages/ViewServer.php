<?php

namespace App\Filament\Resources\Servers\Pages;

use App\Filament\Resources\Servers\ServerResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Actions\Action;
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Icons\Heroicon;
class ViewServer extends ViewRecord
{
    protected static string $resource = ServerResource::class;

    public function infolist(Schema $schema): Schema
    {
        return $schema->schema([
           Section::make('Server Overview')
    ->columns(3)
    ->schema([
        TextEntry::make('name')
            ->label('Server Name')
            ->columnSpanFull()
           // ->size(TextEntry\TextEntrySize::Large)
            ->weight('bold'),

        TextEntry::make('host')
            ->label('Host / Address')
            ->columnSpanFull()
            ->fontFamily('mono'),

        TextEntry::make('public_ip')
            ->label('Public IP')
            ->copyable()
            ->fontFamily('mono'),

        TextEntry::make('private_ip')
            ->label('Private IP')
            ->fontFamily('mono'),

        TextEntry::make('port')
            ->label('SSH Port')
            ->badge(),

        TextEntry::make('username')
            ->label('SSH User')
            ->badge()
            ->color('gray'),

        TextEntry::make('os')
            ->label('Operating System')
            ->color('gray'),
    ])
    ->collapsed(false),

            /* ─────────────────────────────
             | SSH Access (Existing Server)
             |──────────────────────────── */
          Section::make('SSH Access')
    ->description('Grant NanoDeploy SSH access to this server.')
    ->schema([

        // Instructions (always visible)
        TextEntry::make('ssh_help')
            ->state(fn () =>
                'Add the NanoDeploy public key to ~/.ssh/authorized_keys on this server. ' .
                'This key is safe to share. Never expose the private key.'
            )
            ->columnSpanFull(),

        // Active key (if exists)
        TextEntry::make('active_key')
            ->icon(Heroicon::LockOpen)
            ->label('NanoDeploy Public SSH Key')
            ->state(fn ($record) =>
                optional(
                    $record->activeSshKey()->first()
                )->public_key
            )->html()
            ->copyable()
            ->visible(fn ($record) =>
                $record->activeSshKey()->exists()
            )
            ->columnSpanFull(),

        // Generate / Rotate
        Action::make('generate_ssh_key')
            ->label(fn ($record) =>
                $record->activeSshKey()->exists()
                    ? 'Rotate NanoDeploy SSH Key'
                    : 'Generate NanoDeploy SSH Key'
            )
            ->icon('heroicon-o-key')
            ->color('primary')
            ->url(fn () =>
                \App\Filament\Resources\SSHKeys\SSHKeyResource::getUrl('generate', [
                    'server' => request()->route('record'),
                ])
            ),
    ]),


            /* ─────────────────────────────
             | Connection Status
             |──────────────────────────── */
            Section::make('Connection Status')
                ->columns(2)
                ->schema([
                    Text::make('connection_status'),
                    Text::make('last_connection_checked_at'),

                    Action::make('test_connection')
                        ->label('Test Connection')
                        ->icon('heroicon-o-signal')
                        ->requiresConfirmation()
                        ->action(fn() => $this->record->testConnection()),
                ]),
            Section::make('Applications Controls')
                ->columns(3)
                ->schema([
                    Action::make('restart_nginx')
                        ->label('Restart Nginx')
                        ->disabled(false),
                    Action::make('reload_nginx')
                        ->label('Reload Nginx')
                        ->disabled(false),
                    Action::make('restart_mysql')
                        ->label('Restart MySQL')
                        ->disabled(false),
                ])->collapsed(false),
            Section::make('Installed Software')
                ->columns(2)
                ->schema([
                    Text::make('PHP Version'),
                    Action::make('install_php')
                        ->label('Install PHP')
                        ->disabled(false),
                    Text::make('MySQL Version'),
                    Action::make('install_mysql')
                        ->label('Install MySQL')
                        ->disabled(false),
                    Text::make('Redis Version'),
                    Action::make('install_redis')
                        ->label('Install Redis')
                        ->disabled(false),
                ])->collapsed(false),
            Section::make('Server Settings')
                ->collapsed(false)
                ->columns(2)
                ->schema([
                    Text::make('Timezone'),
                    Text::make('Auto Updates'),
                ]),
            Section::make('Danger Zone')
                ->collapsed(false)
                ->schema([
                    Action::make('delete_server')
                        ->label('Delete Server')
                        ->color('danger')
                        ->disabled(),
                ]),
        ]);
    }
}
