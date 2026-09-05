<?php

namespace App\Filament\Resources\Matches\Pages;

use App\Filament\Resources\Matches\MatchResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMatches extends ListRecords
{
    protected static string $resource = MatchResource::class;

    protected static ?string $title = 'Utakmice i rezultati';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
