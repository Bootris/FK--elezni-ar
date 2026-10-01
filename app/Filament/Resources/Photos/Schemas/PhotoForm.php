<?php

namespace App\Filament\Resources\Photos\Schemas;

use App\Models\Photo;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PhotoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Fotografija')
                    ->columns(2)
                    ->components([
                        FileUpload::make('image')
                            ->label('Slika')
                            ->image()
                            ->disk('public')
                            ->directory('gallery')
                            ->imageEditor()
                            ->automaticallyResizeImagesMode('contain')
                            ->automaticallyResizeImagesToWidth('2400')
                            ->automaticallyResizeImagesToHeight('2400')
                            ->automaticallyUpscaleImagesWhenResizing(false)
                            ->maxSize(6144)
                            ->helperText('JPG, PNG ili WebP do 6 MB. Veće fotografije se automatski smanjuju na 2400 px pre slanja.')
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('title')->label('Opis')->maxLength(255),
                        Select::make('album')
                            ->label('Album')
                            ->options(Photo::ALBUMS)
                            ->default('club')
                            ->required()
                            ->native(false),
                        TextInput::make('sort_order')->label('Redosled')->integer()->default(0)->helperText('Manji broj ide prvi. Prazno = 0.'),
                        Toggle::make('visible')->label('Prikaži na sajtu')->default(true),
                    ]),
            ]);
    }
}
