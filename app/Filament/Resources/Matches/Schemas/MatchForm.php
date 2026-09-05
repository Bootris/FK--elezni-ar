<?php

namespace App\Filament\Resources\Matches\Schemas;

use App\Models\FootballMatch;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MatchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make('Utakmica')
                    ->columnSpan(2)
                    ->columns(2)
                    ->components([
                        Select::make('team_type')
                            ->label('Tim')
                            ->options(['first' => 'Prvi tim', 'youth' => 'Omladinska selekcija'])
                            ->default('first')
                            ->required()
                            ->native(false)
                            ->live(),
                        Select::make('youth_selection_id')
                            ->label('Selekcija')
                            ->relationship('selection', 'name')
                            ->native(false)
                            ->visible(fn (callable $get): bool => $get('team_type') === 'youth'),
                        TextInput::make('competition')
                            ->label('Takmičenje')
                            ->required()
                            ->maxLength(255)
                            ->default(fn () => \App\Models\Setting::get('league_name', '')),
                        TextInput::make('round')
                            ->label('Kolo / faza')
                            ->maxLength(255)
                            ->helperText('Npr. 5. kolo, Četvrtfinale kupa.'),
                        TextInput::make('opponent')
                            ->label('Protivnik')
                            ->required()
                            ->maxLength(255),
                        Toggle::make('is_home')
                            ->label('Igramo kod kuće')
                            ->default(true)
                            ->inline(false),
                        DateTimePicker::make('kickoff_at')
                            ->label('Početak')
                            ->seconds(false)
                            ->required()
                            ->displayFormat('d.m.Y H:i'),
                        TextInput::make('venue')
                            ->label('Stadion')
                            ->maxLength(255),
                    ]),

                Section::make('Rezultat')
                    ->columnSpan(1)
                    ->columns(2)
                    ->components([
                        Select::make('status')
                            ->label('Status')
                            ->options(FootballMatch::STATUSES)
                            ->default('scheduled')
                            ->required()
                            ->native(false)
                            ->columnSpanFull(),
                        TextInput::make('our_score')
                            ->label('Naši golovi')
                            ->numeric()
                            ->minValue(0),
                        TextInput::make('their_score')
                            ->label('Golovi protivnika')
                            ->numeric()
                            ->minValue(0),
                        Select::make('post_id')
                            ->label('Izveštaj (vest)')
                            ->relationship('report', 'title')
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->columnSpanFull()
                            ->helperText('Poveži utakmicu sa objavljenom vešću.'),
                        TextInput::make('notes')
                            ->label('Napomena')
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
