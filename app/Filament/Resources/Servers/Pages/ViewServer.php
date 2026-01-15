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
use App\Jobs\ServerConnectionJob;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Enums\TextSize;
use Illuminate\Support\Facades\Log;


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

            Section::make('SSH Access')
                ->description('Grant NanoDeploy SSH access to this server.')
                ->schema([

                    // Instructions (always visible)
                    TextEntry::make('ssh_help')
                        ->state(
                            fn() =>
                            'Add the NanoDeploy public key to ~/.ssh/authorized_keys on this server. ' .
                                'This key is safe to share. Never expose the private key.'
                        )
                        ->columnSpanFull(),

                    // Active key (if exists)
                    TextEntry::make('active_key')
                        ->fontFamily('mono')
                        ->label('Active NanoDeploy SSH Key')
                        ->icon('heroicon-o-key')
                        ->hint('Masked for security')
                        ->hintIcon('heroicon-o-shield-check')
                        ->state(
                            fn($record) =>
                            optional(
                                $record->activeSshKey()->first()
                            )->public_key
                        )->html()
                        ->copyable()
                        ->visible(
                            fn($record) =>
                            $record->activeSshKey()->exists()
                        )
                        ->columnSpanFull(),

                    // Generate / Rotate
                    Action::make('generate_ssh_key')
                        ->label(
                            fn($record) =>
                            $record->activeSshKey()->exists()
                                ? 'Rotate NanoDeploy SSH Key'
                                : 'Generate NanoDeploy SSH Key'
                        )
                        ->icon(fn($record) =>
                        $record->activeSshKey()->exists()
                            ? 'heroicon-o-arrow-path'
                            : 'heroicon-o-key')
                        ->color('success')
                        ->url(
                            fn() =>
                            \App\Filament\Resources\SSHKeys\SSHKeyResource::getUrl('generate', [
                                'server' => request()->route('record'),
                            ])
                        ),
                ]),


            Section::make('Connection Status')
                ->description('Current SSH connectivity state for this server')
                ->columns(2)
                ->icon('heroicon-o-server')
                ->poll('5s')
                ->schema([
                    TextEntry::make('connection_status')
                        ->label('Status')
                        ->icon(fn(?string $state) => match ($state) {
                            'Connected' => 'heroicon-o-check-circle',
                            'Failed'    => 'heroicon-o-x-circle',
                            'Pending'   => 'heroicon-o-arrow-path',
                            default     => 'heroicon-o-question-mark-circle',
                        })
                        ->color(fn(?string $state) => match ($state) {
                            'Connected' => 'success',   // ✅ green
                            'Failed'    => 'danger',    // ✅ red
                            'Pending'   => 'warning',   // ✅ amber
                            default     => 'warning',
                        })
                        ->formatStateUsing(
                            fn(?string $state) =>
                            ucfirst($state ?? 'Unknown')
                        )
                        ->iconPosition(IconPosition::Before)
                        ->weight(FontWeight::SemiBold)
                        ->size(TextSize::Large),
                    TextEntry::make('last_connection_checked_at')
                        ->label('Last Checked')
                        ->dateTime('M d, Y H:i')
                        ->placeholder('Never'),

                    Action::make('test_connection')
                        ->label('Test SSH Connection')
                        ->icon('heroicon-o-signal')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Test SSH Connection')
                        ->modalDescription(
                            'This action will attempt to establish an SSH connection to the server using the configured credentials and SSH keys.The test runs in the background and may take a few seconds. No changes will be made to the server.'
                        )
                        ->modalSubmitActionLabel('Run Connection Test')
                        ->modalIcon('heroicon-o-shield-check')
                        ->action(function () {
                            if (! $this->record) {
                                return;
                            }
                            ServerConnectionJob::dispatch($this->record);
                            Notification::make()
                                ->title('SSH Connection Test Started')
                                ->body(
                                    'We are currently testing the SSH connection to this server.
                    You will be notified once the test completes.'
                                )
                                ->success()
                                ->send();
                        }),

                ]),
            Section::make('Applications Controls')
                ->columns(3)
                ->schema([
                    Action::make('restart_nginx')
                        ->label('Restart Nginx')
                        ->color('gray')
                        ->disabled(false),
                    Action::make('reload_nginx')
                        ->label('Reload Nginx')
                        ->color('gray')
                        ->disabled(false),
                    Action::make('restart_mysql')
                        ->label('Restart MySQL')
                        ->color('gray')
                        ->disabled(false),
                ])->collapsed(false),
            Section::make('Installed Software')
                ->columns(2)
                ->schema([
                    Text::make('PHP Version'),
                    Action::make('install_php')
                        ->label('Install PHP')
                        ->color('info')
                        ->disabled(false),
                    Text::make('MySQL Version'),
                    Action::make('install_mysql')
                        ->color('info')
                        ->label('Install MySQL')
                        ->disabled(false),
                    Text::make('Redis Version'),
                    Action::make('install_redis')
                        ->color('info')
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
