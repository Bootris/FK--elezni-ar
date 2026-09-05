<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Player extends Model
{
    public const POSITIONS = [
        'GK' => 'Golman',
        'DF' => 'Odbrana',
        'MF' => 'Vezni red',
        'FW' => 'Napad',
    ];

    protected $fillable = [
        'name', 'slug', 'shirt_number', 'position', 'nationality', 'birth_date', 'height_cm',
        'joined_year', 'previous_club', 'photo', 'bio', 'is_captain', 'from_academy',
        'sort_order', 'visible',
    ];

    protected static function booted(): void
    {
        static::creating(function (Player $player) {
            $player->slug = $player->slug ?: Str::slug($player->name);
        });
    }

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'is_captain' => 'boolean',
            'from_academy' => 'boolean',
            'visible' => 'boolean',
        ];
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('visible', true)
            ->orderByRaw("case position when 'GK' then 0 when 'DF' then 1 when 'MF' then 2 else 3 end")
            ->orderBy('sort_order')
            ->orderBy('shirt_number');
    }

    public function getAgeAttribute(): ?int
    {
        return $this->birth_date?->age;
    }

    public function getPositionLabelAttribute(): string
    {
        return __('club.positions.' . $this->position);
    }
}
