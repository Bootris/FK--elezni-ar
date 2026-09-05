<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class StandingRow extends Model
{
    protected $fillable = [
        'competition', 'position', 'team', 'played', 'won', 'drawn', 'lost',
        'goals_for', 'goals_against', 'points', 'form', 'is_club',
    ];

    protected function casts(): array
    {
        return [
            'is_club' => 'boolean',
        ];
    }

    public function scopeTable(Builder $query, string $competition = 'first'): Builder
    {
        return $query->where('competition', $competition)->orderBy('position');
    }

    public function getGoalDifferenceAttribute(): int
    {
        return $this->goals_for - $this->goals_against;
    }
}
