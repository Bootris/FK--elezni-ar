<?php

namespace App\Filament\Resources\YouthApplications;

use App\Filament\Resources\YouthApplications\Pages\ListYouthApplications;
use App\Filament\Resources\YouthApplications\Pages\ViewYouthApplication;
use App\Filament\Resources\YouthApplications\Schemas\YouthApplicationInfolist;
use App\Filament\Resources\YouthApplications\Tables\YouthApplicationsTable;
use App\Models\YouthApplication;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class YouthApplicationResource extends Resource
{
    protected static ?string $model = YouthApplication::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static string|\UnitEnum|null $navigationGroup = 'Prijave';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Upis omladinaca';

    protected static ?string $modelLabel = 'prijava';

    protected static ?string $pluralModelLabel = 'prijave za upis';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('status', 'new')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function infolist(Schema $schema): Schema
    {
        return YouthApplicationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return YouthApplicationsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListYouthApplications::route('/'),
            'view' => ViewYouthApplication::route('/{record}'),
        ];
    }
}
