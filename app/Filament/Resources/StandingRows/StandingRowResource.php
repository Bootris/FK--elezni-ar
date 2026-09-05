<?php

namespace App\Filament\Resources\StandingRows;

use App\Filament\Resources\StandingRows\Pages\CreateStandingRow;
use App\Filament\Resources\StandingRows\Pages\EditStandingRow;
use App\Filament\Resources\StandingRows\Pages\ListStandingRows;
use App\Filament\Resources\StandingRows\Schemas\StandingRowForm;
use App\Filament\Resources\StandingRows\Tables\StandingRowsTable;
use App\Models\StandingRow;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StandingRowResource extends Resource
{
    protected static ?string $model = StandingRow::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTableCells;

    protected static string|\UnitEnum|null $navigationGroup = 'Prvi tim';

    protected static ?int $navigationSort = 13;

    protected static ?string $navigationLabel = 'Tabela';

    protected static ?string $modelLabel = 'red tabele';

    protected static ?string $pluralModelLabel = 'tabela';

    protected static ?string $recordTitleAttribute = 'team';

    public static function form(Schema $schema): Schema
    {
        return StandingRowForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StandingRowsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStandingRows::route('/'),
            'create' => CreateStandingRow::route('/create'),
            'edit' => EditStandingRow::route('/{record}/edit'),
        ];
    }
}
