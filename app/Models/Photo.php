<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    public const ALBUMS = [
        'first_team' => 'Prvi tim',
        'youth' => 'Omladinci',
        'club' => 'Klub',
    ];

    protected $fillable = ['title', 'image', 'album', 'sort_order', 'visible'];

    protected function casts(): array
    {
        return [
            'visible' => 'boolean',
        ];
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('visible', true)->orderBy('sort_order')->orderByDesc('id');
    }

    public function scopeAlbum(Builder $query, string $album): Builder
    {
        return $query->where('album', $album);
    }
}
