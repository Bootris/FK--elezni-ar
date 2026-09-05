<?php

namespace App\Filament\Resources\StaffMembers\Schemas;

use App\Models\StaffMember;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class StaffMemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make('Profil')
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
                        TextInput::make('role')
                            ->label('Funkcija')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Npr. Šef stručnog štaba, Trener golmana, Trener U-11.'),
                        Select::make('department')
                            ->label('Deo kluba')
                            ->options(StaffMember::DEPARTMENTS)
                            ->default('first_team')
                            ->required()
                            ->native(false)
                            ->live(),
                        Select::make('youth_selection_id')
                            ->label('Selekcija (za trenere omladinaca)')
                            ->relationship('selection', 'name')
                            ->native(false)
                            ->visible(fn (callable $get): bool => $get('department') === 'youth'),
                        TextInput::make('licence')
                            ->label('Trenerska licenca')
                            ->maxLength(255)
                            ->helperText('Npr. UEFA A, UEFA B.'),
                        Textarea::make('bio')
                            ->label('Biografija')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),

                Section::make('Fotografija i kontakt')
                    ->columnSpan(1)
                    ->components([
                        FileUpload::make('photo')
                            ->label('Fotografija')
                            ->image()
                            ->disk('public')
                            ->directory('staff')
                            ->imageEditor()
                            ->maxSize(4096),
                        TextInput::make('email')->label('Imejl')->email()->maxLength(255),
                        TextInput::make('phone')->label('Telefon')->tel()->maxLength(255),
                        TextInput::make('sort_order')->label('Redosled')->numeric()->default(0),
                        Toggle::make('visible')->label('Prikaži na sajtu')->default(true),
                    ]),
            ]);
    }
}
