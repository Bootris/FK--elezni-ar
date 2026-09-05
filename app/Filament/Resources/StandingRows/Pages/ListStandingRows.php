<?php

namespace App\Filament\Resources\StandingRows\Pages;

use App\Filament\Resources\StandingRows\StandingRowResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStandingRows extends ListRecords
{
    protected static string $resource = StandingRowResource::class;

    protected static ?string $title = 'Tabela';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
