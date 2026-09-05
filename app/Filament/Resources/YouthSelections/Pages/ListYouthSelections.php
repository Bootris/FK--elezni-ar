<?php

namespace App\Filament\Resources\YouthSelections\Pages;

use App\Filament\Resources\YouthSelections\YouthSelectionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListYouthSelections extends ListRecords
{
    protected static string $resource = YouthSelectionResource::class;

    protected static ?string $title = 'Selekcije';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
