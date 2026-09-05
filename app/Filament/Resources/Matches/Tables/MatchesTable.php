<?php

namespace App\Filament\Resources\Matches\Tables;

use App\Models\FootballMatch;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MatchesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('kickoff_at', 'desc')
            ->columns([
                TextColumn::make('kickoff_at')->label('Datum')->dateTime('d.m.Y H:i')->sortable(),
                TextColumn::make('competition')->label('Takmičenje')->description(fn (FootballMatch $r): ?string => $r->round),
                TextColumn::make('opponent')
                    ->label('Protivnik')
                    ->searchable()
                    ->formatStateUsing(fn (string $state, FootballMatch $r): string => ($r->is_home ? 'vs ' : '@ ') . $state),
                TextColumn::make('score_line')
                    ->label('Rezultat')
                    ->state(fn (FootballMatch $r): string => $r->score_line ?? '—')
                    ->badge()
                    ->color(fn (FootballMatch $r): string => match ($r->outcome) {
                        'W' => 'success',
                        'L' => 'danger',
                        'D' => 'gray',
                        default => 'info',
                    }),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color('gray')
                    ->formatStateUsing(fn (string $state): string => FootballMatch::STATUSES[$state] ?? $state),
                TextColumn::make('team_type')
                    ->label('Tim')
                    ->formatStateUsing(fn (string $state, FootballMatch $r): string => $state === 'first' ? 'Prvi tim' : ($r->selection?->name ?? 'Omladinci')),
            ])
            ->filters([
                SelectFilter::make('status')->label('Status')->options(FootballMatch::STATUSES),
                SelectFilter::make('team_type')->label('Tim')->options(['first' => 'Prvi tim', 'youth' => 'Omladinci']),
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
