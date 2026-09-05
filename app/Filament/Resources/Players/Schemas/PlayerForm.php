<?php

namespace App\Filament\Resources\Players\Schemas;

use App\Models\Player;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PlayerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make('Igrač')
                    ->columnSpan(2)
                    ->columns(2)
                    ->components([
                        TextInput::make('name')
                            ->label('Ime i prezime')
                            ->required()
                            ->maxLength(255)
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
                        TextInput::make('shirt_number')
                            ->label('Broj na dresu')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(99),
                        Select::make('position')
                            ->label('Pozicija')
                            ->options(Player::POSITIONS)
                            ->default('MF')
                            ->required()
                            ->native(false),
                        DatePicker::make('birth_date')
                            ->label('Datum rođenja')
                            ->displayFormat('d.m.Y'),
                        TextInput::make('nationality')
                            ->label('Državljanstvo')
                            ->default('Srbija')
                            ->maxLength(255),
                        TextInput::make('height_cm')
                            ->label('Visina (cm)')
                            ->numeric(),
                        TextInput::make('joined_year')
                            ->label('U klubu od (godina)')
                            ->numeric(),
                        TextInput::make('previous_club')
                            ->label('Prethodni klub')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Textarea::make('bio')
                            ->label('Kratka biografija')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),

                Section::make('Fotografija i prikaz')
                    ->columnSpan(1)
                    ->components([
                        FileUpload::make('photo')
                            ->label('Fotografija')
                            ->image()
                            ->disk('public')
                            ->directory('players')
                            ->imageEditor()
                            ->maxSize(4096)
                            ->helperText('Najbolje portret 3:4 na jednobojnoj pozadini.'),
                        Toggle::make('is_captain')->label('Kapiten'),
                        Toggle::make('from_academy')
                            ->label('Iz omladinske škole')
                            ->helperText('Prikazuje oznaku „Naš klinac“ na kartici igrača.'),
                        TextInput::make('sort_order')
                            ->label('Redosled')
                            ->numeric()
                            ->default(0),
                        Toggle::make('visible')
                            ->label('Prikaži na sajtu')
                            ->default(true),
                    ]),
            ]);
    }
}
