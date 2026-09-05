<?php

namespace App\Filament\Resources\StandingRows\Tables;

use App\Models\StandingRow;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

/** Numbers are editable straight in the table — no need to open every row after a match day. */
class StandingRowsTable
{
    public static function configure(Table $table): Table
    {
        $num = fn (string $name, string $label) => TextInputColumn::make($name)->label($label)->type('number')->rules(['integer', 'min:0'])->width('4.5rem');

        return $table
            ->defaultSort('position')
            ->paginated(false)
            ->columns([
                TextInputColumn::make('position')->label('#')->type('number')->rules(['integer', 'min:1'])->width('4rem'),
                TextColumn::make('team')
                    ->label('Klub')
                    ->searchable()
                    ->weight(fn (StandingRow $r) => $r->is_club ? 'bold' : null),
                $num('played', 'OU'),
                $num('won', 'P'),
                $num('drawn', 'N'),
                $num('lost', 'I'),
                $num('goals_for', 'DG'),
                $num('goals_against', 'PG'),
                $num('points', 'Bod'),
                TextInputColumn::make('form')->label('Forma')->width('6rem'),
                IconColumn::make('is_club')->label('Mi')->boolean(),
            ])
            ->filters([
                SelectFilter::make('competition')
                    ->label('Tabela')
                    ->options(fn () => StandingRow::query()->distinct()->pluck('competition', 'competition')->all()),
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
