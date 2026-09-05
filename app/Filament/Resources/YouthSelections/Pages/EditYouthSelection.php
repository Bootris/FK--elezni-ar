<?php

namespace App\Filament\Resources\YouthSelections\Pages;

use App\Filament\Resources\YouthSelections\YouthSelectionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditYouthSelection extends EditRecord
{
    protected static string $resource = YouthSelectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
