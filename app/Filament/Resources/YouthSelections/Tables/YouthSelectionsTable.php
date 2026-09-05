<?php

namespace App\Filament\Resources\YouthSelections\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class YouthSelectionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('name')->label('Selekcija')->searchable()->sortable(),
                TextColumn::make('birth_years')->label('Godišta'),
                TextColumn::make('training_schedule')->label('Treninzi')->wrap(),
                TextColumn::make('coaches_count')->label('Treneri')->counts('coaches'),
                TextColumn::make('applications_count')->label('Prijave')->counts('applications')->badge()->color('warning'),
                IconColumn::make('accepting_applications')->label('Upis')->boolean(),
                IconColumn::make('visible')->label('Vidljiva')->boolean(),
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
