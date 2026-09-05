<?php

namespace App\Filament\Resources\YouthSelections\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class YouthSelectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make('Selekcija')
                    ->columnSpan(2)
                    ->columns(2)
                    ->components([
                        TextInput::make('name')
                            ->label('Naziv')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Npr. U-9, U-13 · Petlići, Kadeti.')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, ?string $state, callable $set) {
                                if ($operation === 'create') {
                                    $set('slug', Str::slug((string) $state));
                                }
                            }),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->rules(['alpha_dash'])
                            ->unique(ignoreRecord: true),
                        TextInput::make('birth_years')
                            ->label('Godišta')
                            ->maxLength(255)
                            ->helperText('Npr. 2016/2017.'),
                        TextInput::make('training_schedule')
                            ->label('Termini treninga')
                            ->maxLength(255)
                            ->helperText('Npr. Pon, Sre, Pet · 17:00–18:30.'),
                        TextInput::make('training_venue')
                            ->label('Mesto treninga')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->label('Opis')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),

                Section::make('Prikaz')
                    ->columnSpan(1)
                    ->components([
                        FileUpload::make('photo')
                            ->label('Fotografija')
                            ->image()
                            ->disk('public')
                            ->directory('selections')
                            ->imageEditor()
                            ->maxSize(4096),
                        Toggle::make('accepting_applications')
                            ->label('Prima nove igrače')
                            ->default(true)
                            ->helperText('Isključi kad je selekcija popunjena — nestaje iz forme za upis.'),
                        TextInput::make('sort_order')->label('Redosled')->numeric()->default(0),
                        Toggle::make('visible')->label('Prikaži na sajtu')->default(true),
                    ]),
            ]);
    }
}
