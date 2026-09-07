<?php

namespace App\Filament\Resources\YouthApplications\Schemas;

use App\Models\YouthApplication;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class YouthApplicationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Dete')
                    ->columns(3)
                    ->components([
                        TextEntry::make('child_name')->label('Ime i prezime'),
                        TextEntry::make('birth_year')->label('Godina rođenja'),
                        TextEntry::make('selection.name')->label('Željena selekcija')->placeholder('—'),
                    ]),
                Section::make('Roditelj / staratelj')
                    ->columns(3)
                    ->components([
                        TextEntry::make('parent_name')->label('Ime i prezime'),
                        TextEntry::make('phone')->label('Telefon')->url(fn (YouthApplication $r) => 'tel:' . preg_replace('/[^+\d]/', '', $r->phone)),
                        TextEntry::make('email')->label('Imejl')->placeholder('—')->url(fn (YouthApplication $r) => $r->email ? 'mailto:' . $r->email : null),
                    ]),
                Section::make('Napomena')
                    ->components([
                        TextEntry::make('note')->hiddenLabel()->placeholder('Bez napomene.')->columnSpanFull(),
                    ]),
                Section::make('Obrada')
                    ->columns(3)
                    ->components([
                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->formatStateUsing(fn (string $state): string => YouthApplication::STATUSES[$state] ?? $state)
                            ->color(fn (string $state): string => match ($state) {
                                'new' => 'warning',
                                'contacted' => 'info',
                                'enrolled' => 'success',
                                default => 'gray',
                            }),
                        TextEntry::make('created_at')->label('Primljeno')->dateTime('d.m.Y H:i'),
                        TextEntry::make('read_at')->label('Pročitano')->dateTime('d.m.Y H:i')->placeholder('—'),
                        TextEntry::make('admin_note')->label('Interna beleška')->placeholder('—')->columnSpanFull(),
                    ]),
            ]);
    }
}
