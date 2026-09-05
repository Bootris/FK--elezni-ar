<?php

namespace App\Filament\Resources\YouthApplications\Pages;

use App\Filament\Resources\YouthApplications\YouthApplicationResource;
use App\Models\YouthApplication;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

class ViewYouthApplication extends ViewRecord
{
    protected static string $resource = YouthApplicationResource::class;

    protected static ?string $title = 'Prijava za upis';

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
            Action::make('process')
                ->label('Obradi prijavu')
                ->icon(Heroicon::OutlinedPencilSquare)
                ->form([
                    Select::make('status')
                        ->label('Status')
                        ->options(YouthApplication::STATUSES)
                        ->default(fn () => $this->record->status)
                        ->required()
                        ->native(false),
                    Textarea::make('admin_note')
                        ->label('Interna beleška')
                        ->default(fn () => $this->record->admin_note)
                        ->rows(3),
                ])
                ->action(function (array $data): void {
                    $this->record->update($data);
                    Notification::make()->title('Prijava ažurirana')->success()->send();
                }),
            Action::make('call')
                ->label('Pozovi')
                ->icon(Heroicon::OutlinedPhone)
                ->url(fn (): string => 'tel:' . preg_replace('/[^+\d]/', '', $this->record->phone)),
            Action::make('reply')
                ->label('Odgovori imejlom')
                ->icon(Heroicon::OutlinedEnvelope)
                ->url(fn (): string => 'mailto:' . $this->record->email
                    . '?subject=' . rawurlencode('Upis u omladinsku školu — ' . $this->record->child_name))
                ->openUrlInNewTab(),
            DeleteAction::make(),
        ];
    }
}
