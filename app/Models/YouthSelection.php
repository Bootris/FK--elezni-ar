<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class YouthSelection extends Model
{
    protected $fillable = [
        'name', 'slug', 'birth_years', 'description', 'training_schedule', 'training_venue',
        'photo', 'accepting_applications', 'sort_order', 'visible',
    ];

    protected static function booted(): void
    {
        static::creating(function (YouthSelection $selection) {
            $selection->slug = $selection->slug ?: Str::slug($selection->name);
        });
    }

    protected function casts(): array
    {
        return [
            'accepting_applications' => 'boolean',
            'visible' => 'boolean',
        ];
    }

    public function coaches(): HasMany
    {
        return $this->hasMany(StaffMember::class)->visible();
    }

    public function applications(): HasMany
    {
        return $this->hasMany(YouthApplication::class);
    }

    public function matches(): HasMany
    {
        return $this->hasMany(FootballMatch::class);
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('visible', true)->orderBy('sort_order')->orderBy('name');
    }
}
