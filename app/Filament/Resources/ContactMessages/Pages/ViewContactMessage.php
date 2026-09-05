<?php

namespace App\Filament\Resources\ContactMessages\Pages;

use App\Filament\Resources\ContactMessages\ContactMessageResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

class ViewContactMessage extends ViewRecord
{
    protected static string $resource = ContactMessageResource::class;

    protected static ?string $title = 'Poruka';

    public function mount(int|string $record): void
    {
        parent::mount($record);

        if (! $this->record->read_at) {
            $this->record->update(['read_at' => now()]);
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('reply')
                ->label('Odgovori imejlom')
                ->icon(Heroicon::OutlinedArrowUturnLeft)
                ->url(fn (): string => 'mailto:' . $this->record->email
                    . '?subject=' . rawurlencode('Re: ' . ($this->record->subject ?: 'Vaša poruka')))
                ->openUrlInNewTab(),
            DeleteAction::make(),
        ];
    }
}
