<?php

namespace App\Filament\Resources\Servers\Pages;

use App\Filament\Resources\Servers\ServerResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Actions\Action;

class ViewServer extends ViewRecord
{
    protected static string $resource = ServerResource::class;

    public function infolist(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Server Overview')
                ->columns(3)
                ->schema([
                    Text::make('Server Name')->columnSpanFull(),
                    Text::make('Public IP')->copyable(),
                    Text::make('Private IP')->copyable(),
                    Text::make('Provider')->columnSpanFull(),
                    Text::make('Region'),
                    Text::make('OS'),
                    Text::make('Last Heartbeat'),
                ])->collapsed(false),
            Section::make('Connection Details')
                ->columns(2)
                ->schema([
                    Text::make('SSH Username'),
                    Text::make('SSH Port'),
                    Text::make('Public IP')->copyable(),
                    Text::make('Private IP'),
                    Text::make('SSH Command')
                        ->copyable()
                        ->columnSpanFull(),
                ])->collapsed(false),
            Section::make('Service Controls')
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
