<?php

namespace App\Filament\Resources\ContactMessages\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ContactMessagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('read_at')
                    ->label('')
                    ->badge()
                    ->state(fn ($record): string => $record->read_at ? 'Pročitano' : 'Novo')
                    ->color(fn (string $state): string => $state === 'Novo' ? 'warning' : 'gray'),
                TextColumn::make('name')
                    ->label('Ime')
                    ->searchable()
                    ->weight(fn ($record) => $record->read_at ? null : FontWeight::Bold),
                TextColumn::make('email')
                    ->label('Imejl')
                    ->searchable(),
                TextColumn::make('subject')
                    ->label('Tema')
                    ->searchable()
                    ->limit(50)
                    ->placeholder('—'),
                TextColumn::make('created_at')
                    ->label('Primljeno')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('read_at')
                    ->label('Pročitano')
                    ->nullable(),
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
