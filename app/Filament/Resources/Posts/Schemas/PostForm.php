<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make('Sadržaj')
                    ->columnSpan(2)
                    ->components([
                        TextInput::make('title')
                            ->label('Naslov')
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
                            ->unique(ignoreRecord: true)
                            ->helperText('Deo adrese vesti, npr. /vesti/naslov-vesti'),
                        Textarea::make('excerpt')
                            ->label('Kratak uvod')
                            ->rows(3)
                            ->maxLength(500)
                            ->helperText('Sažetak koji se prikazuje na karticama i u pretraživačima.'),
                        RichEditor::make('body')
                            ->label('Tekst')
                            ->required()
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('posts/attachments'),
                    ]),

                Section::make('Objavljivanje')
                    ->columnSpan(1)
                    ->components([
                        Select::make('status')
                            ->options([
                                'draft' => 'Nacrt',
                                'published' => 'Objavljeno',
                            ])
                            ->label('Status')
                            ->default('draft')
                            ->required()
                            ->native(false)
                            ->live(),
                        DateTimePicker::make('published_at')
                            ->label('Datum objave')
                            ->seconds(false)
                            ->default(now())
                            ->helperText('Datum u budućnosti = zakazano; vest se pojavljuje kad prođe vreme.'),
                        Select::make('category_id')
                            ->label('Kategorija')
                            ->relationship('category', 'name')
                            ->createOptionForm([
                                TextInput::make('name')->required()->maxLength(255),
                            ])
                            ->native(false),
                        Toggle::make('show_on_home')
                            ->label('Istakni na početnoj (glavna vest)'),
                        Toggle::make('is_pinned')
                            ->label('Pinovana vest (traka na vrhu sajta)')
                            ->helperText('Pinovane vesti se vrte u traci na vrhu svake strane. Ako nijedna nije pinovana, vrte se najnovije.'),
                    ]),

                Section::make('Mediji')
                    ->columnSpan(1)
                    ->components([
                        FileUpload::make('featured_image')
                            ->label('Naslovna fotografija')
                            ->image()
                            ->disk('public')
                            ->directory('posts')
                            ->imageEditor()
                            ->maxSize(4096)
                            ->helperText('Prikazuje se na vrhu vesti i na karticama.'),
                        TextInput::make('video_url')
                            ->label('Video (YouTube / Vimeo link)')
                            ->url()
                            ->helperText('Vest sa videom se automatski pojavljuje i u sekciji Video.'),
                    ]),

                Section::make('SEO')
                    ->columnSpan(1)
                    ->collapsed()
                    ->components([
                        TextInput::make('seo_title')
                            ->label('SEO naslov')
                            ->maxLength(255)
                            ->helperText('Ako je prazno, koristi se naslov vesti.'),
                        Textarea::make('seo_description')
                            ->label('SEO opis')
                            ->rows(2)
                            ->maxLength(500)
                            ->helperText('Ako je prazno, koristi se kratak uvod.'),
                    ]),
            ]);
    }
}
