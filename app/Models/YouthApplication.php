<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class YouthApplication extends Model
{
    public const STATUSES = [
        'new' => 'Nova',
        'contacted' => 'Kontaktirani',
        'enrolled' => 'Upisan',
        'rejected' => 'Odbijena',
    ];

    protected $fillable = [
        'child_name', 'birth_year', 'parent_name', 'phone', 'email', 'youth_selection_id',
        'note', 'status', 'admin_note', 'read_at',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    public function selection(): BelongsTo
    {
        return $this->belongsTo(YouthSelection::class, 'youth_selection_id');
    }
}
