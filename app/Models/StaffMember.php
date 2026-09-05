<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class StaffMember extends Model
{
    public const DEPARTMENTS = [
        'first_team' => 'Prvi tim',
        'youth' => 'Omladinska škola',
        'club' => 'Uprava kluba',
    ];

    protected $fillable = [
        'name', 'slug', 'role', 'department', 'youth_selection_id', 'licence', 'photo', 'bio',
        'email', 'phone', 'sort_order', 'visible',
    ];

    protected static function booted(): void
    {
        static::creating(function (StaffMember $member) {
            $member->slug = $member->slug ?: Str::slug($member->name);
        });
    }

    protected function casts(): array
    {
        return [
            'visible' => 'boolean',
        ];
    }

    public function selection(): BelongsTo
    {
        return $this->belongsTo(YouthSelection::class, 'youth_selection_id');
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('visible', true)->orderBy('sort_order')->orderBy('name');
    }

    public function scopeDepartment(Builder $query, string $department): Builder
    {
        return $query->where('department', $department);
    }
}
