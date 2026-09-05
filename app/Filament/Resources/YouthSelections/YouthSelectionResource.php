<?php

namespace App\Filament\Resources\YouthSelections;

use App\Filament\Resources\YouthSelections\Pages\CreateYouthSelection;
use App\Filament\Resources\YouthSelections\Pages\EditYouthSelection;
use App\Filament\Resources\YouthSelections\Pages\ListYouthSelections;
use App\Filament\Resources\YouthSelections\Schemas\YouthSelectionForm;
use App\Filament\Resources\YouthSelections\Tables\YouthSelectionsTable;
use App\Models\YouthSelection;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class YouthSelectionResource extends Resource
{
    protected static ?string $model = YouthSelection::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static string|\UnitEnum|null $navigationGroup = 'Omladinci';

    protected static ?int $navigationSort = 20;

    protected static ?string $navigationLabel = 'Selekcije';

    protected static ?string $modelLabel = 'selekcija';

    protected static ?string $pluralModelLabel = 'selekcije';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return YouthSelectionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return YouthSelectionsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListYouthSelections::route('/'),
            'create' => CreateYouthSelection::route('/create'),
            'edit' => EditYouthSelection::route('/{record}/edit'),
        ];
    }
}
