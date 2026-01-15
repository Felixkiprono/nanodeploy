<?php

namespace App\Filament\Resources\SSHKeys;

use App\Filament\Resources\SSHKeys\Pages\CreateSSHKey;
use App\Filament\Resources\SSHKeys\Pages\EditSSHKey;
use App\Filament\Resources\SSHKeys\Pages\ListSSHKeys;
use App\Filament\Resources\SSHKeys\Pages\ViewSSHKey;
use App\Filament\Resources\SSHKeys\Schemas\SSHKeyForm;
use App\Filament\Resources\SSHKeys\Schemas\SSHKeyInfolist;
use App\Filament\Resources\SSHKeys\Tables\SSHKeysTable;
use App\Models\SSHKey;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SSHKeyResource extends Resource
{
    protected static ?string $model = SSHKey::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLockClosed;

    protected static ?string $recordTitleAttribute = 'SSHKey';

    protected static ?string $navigationLabel = 'SSH Keys';

    protected static ?string $pluralLabel = 'SSH Keys';

    protected static ?string $label = 'SSH Key';

    public static function form(Schema $schema): Schema
    {
        return SSHKeyForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SSHKeyInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SSHKeysTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSSHKeys::route('/'),
            'create' => CreateSSHKey::route('/create'),
            'generate' => Pages\GenerateSSHKey::route('/generate'),
            'view' => ViewSSHKey::route('/{record}'),
            'edit' => EditSSHKey::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
