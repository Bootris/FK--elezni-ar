<?php

namespace App\Filament\Resources\StandingRows\Pages;

use App\Filament\Resources\StandingRows\StandingRowResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStandingRow extends EditRecord
{
    protected static string $resource = StandingRowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
