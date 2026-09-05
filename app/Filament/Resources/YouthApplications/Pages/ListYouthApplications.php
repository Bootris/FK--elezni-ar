<?php

namespace App\Filament\Resources\YouthApplications\Pages;

use App\Filament\Resources\YouthApplications\YouthApplicationResource;
use Filament\Resources\Pages\ListRecords;

class ListYouthApplications extends ListRecords
{
    protected static string $resource = YouthApplicationResource::class;

    protected static ?string $title = 'Prijave za upis';
}
