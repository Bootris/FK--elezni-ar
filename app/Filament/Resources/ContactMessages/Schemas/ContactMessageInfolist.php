<?php

namespace App\Filament\Resources\ContactMessages\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ContactMessageInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')->label('Ime'),
                TextEntry::make('email')
                    ->label('Imejl'),
                TextEntry::make('phone')
                    ->label('Telefon')
                    ->placeholder('-'),
                TextEntry::make('subject')
                    ->label('Tema')
                    ->placeholder('-'),
                TextEntry::make('message')
                    ->label('Poruka')
                    ->columnSpanFull(),
                TextEntry::make('read_at')
                    ->label('Pročitano')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->label('Primljeno')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label('Izmenjeno')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
