<?php

namespace App\Filament\Resources\YouthApplications\Tables;

use App\Models\YouthApplication;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class YouthApplicationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('child_name')
                    ->label('Dete')
                    ->searchable()
                    ->weight(fn (YouthApplication $r) => $r->read_at ? null : FontWeight::Bold)
                    ->description(fn (YouthApplication $r): string => 'god. ' . $r->birth_year),
                TextColumn::make('selection.name')->label('Selekcija')->placeholder('—'),
                TextColumn::make('parent_name')->label('Roditelj')->searchable(),
                TextColumn::make('phone')->label('Telefon')->searchable(),
                TextColumn::make('email')->label('Imejl')->searchable()->toggleable(),
                SelectColumn::make('status')
                    ->label('Status')
                    ->options(YouthApplication::STATUSES)
                    ->selectablePlaceholder(false),
                TextColumn::make('created_at')->label('Primljeno')->dateTime('d.m.Y H:i')->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->label('Status')->options(YouthApplication::STATUSES),
                SelectFilter::make('youth_selection_id')->label('Selekcija')->relationship('selection', 'name'),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
