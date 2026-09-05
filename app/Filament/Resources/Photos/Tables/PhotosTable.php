<?php

namespace App\Filament\Resources\Photos\Tables;

use App\Models\Photo;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PhotosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                ImageColumn::make('image')->label('')->disk('public')->square()->size(64),
                TextColumn::make('title')->label('Opis')->searchable()->placeholder('—'),
                TextColumn::make('album')
                    ->label('Album')
                    ->badge()
                    ->color('gray')
                    ->formatStateUsing(fn (string $state): string => Photo::ALBUMS[$state] ?? $state),
                IconColumn::make('visible')->label('Vidljiva')->boolean(),
                TextColumn::make('created_at')->label('Dodato')->dateTime('d.m.Y')->sortable(),
            ])
            ->filters([
                SelectFilter::make('album')->label('Album')->options(Photo::ALBUMS),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
