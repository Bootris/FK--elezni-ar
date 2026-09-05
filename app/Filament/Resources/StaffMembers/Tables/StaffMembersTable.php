<?php

namespace App\Filament\Resources\StaffMembers\Tables;

use App\Models\StaffMember;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class StaffMembersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                ImageColumn::make('photo')->label('')->disk('public')->circular(),
                TextColumn::make('name')->label('Ime')->searchable()->sortable(),
                TextColumn::make('role')->label('Funkcija')->searchable(),
                TextColumn::make('department')
                    ->label('Deo kluba')
                    ->badge()
                    ->color('gray')
                    ->formatStateUsing(fn (string $state): string => StaffMember::DEPARTMENTS[$state] ?? $state),
                TextColumn::make('selection.name')->label('Selekcija')->placeholder('—'),
                TextColumn::make('licence')->label('Licenca')->placeholder('—'),
                IconColumn::make('visible')->label('Vidljiv')->boolean(),
            ])
            ->filters([
                SelectFilter::make('department')->label('Deo kluba')->options(StaffMember::DEPARTMENTS),
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
