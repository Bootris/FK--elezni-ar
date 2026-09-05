<?php

namespace App\Filament\Resources\StandingRows\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StandingRowForm
{
    public static function configure(Schema $schema): Schema
    {
        $num = fn (string $name, string $label) => TextInput::make($name)->label($label)->numeric()->minValue(0)->default(0)->required();

        return $schema
            ->components([
                Section::make('Red tabele')
                    ->columns(4)
                    ->components([
                        TextInput::make('competition')
                            ->label('Tabela (ključ)')
                            ->default('first')
                            ->required()
                            ->helperText('„first“ je tabela prvog tima; drugi ključ pravi novu tabelu.'),
                        TextInput::make('position')->label('Pozicija')->numeric()->minValue(1)->required(),
                        TextInput::make('team')->label('Klub')->required()->columnSpan(2),
                        $num('played', 'Odigrano'),
                        $num('won', 'Pobede'),
                        $num('drawn', 'Nerešeno'),
                        $num('lost', 'Porazi'),
                        $num('goals_for', 'Dati golovi'),
                        $num('goals_against', 'Primljeni golovi'),
                        $num('points', 'Bodovi'),
                        TextInput::make('form')->label('Forma')->maxLength(10)->helperText('Npr. WWDLW (poslednje utakmice).'),
                        Toggle::make('is_club')->label('Ovo je naš klub (istaknuto)')->columnSpanFull(),
                    ]),
            ]);
    }
}
