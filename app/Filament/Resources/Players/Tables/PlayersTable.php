<?php

namespace App\Filament\Resources\Players\Tables;

use App\Models\Player;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PlayersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                ImageColumn::make('photo')->label('')->disk('public')->circular(),
                TextColumn::make('shirt_number')->label('#')->sortable(),
                TextColumn::make('name')->label('Ime')->searchable()->sortable()
                    ->description(fn (Player $record): ?string => $record->is_captain ? 'Kapiten' : null),
                TextColumn::make('position')
                    ->label('Pozicija')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Player::POSITIONS[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        'GK' => 'warning',
                        'DF' => 'info',
                        'MF' => 'success',
                        default => 'danger',
                    }),
                TextColumn::make('birth_date')->label('Rođen')->date('d.m.Y')->sortable(),
                TextColumn::make('height_cm')->label('Visina')->suffix(' cm')->sortable()->toggleable(),
                TextColumn::make('weight_kg')->label('Težina')->suffix(' kg')->sortable()->toggleable(),
                IconColumn::make('from_academy')->label('Škola')->boolean(),
                IconColumn::make('visible')->label('Vidljiv')->boolean(),
            ])
            ->filters([
                SelectFilter::make('position')->label('Pozicija')->options(Player::POSITIONS),
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
