<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * `sort_order` is NOT NULL with a default of 0, but an emptied admin field
 * arrives as null and the insert would fail. Treat blank as 0 instead.
 */
trait HasSortOrder
{
    protected function sortOrder(): Attribute
    {
        return Attribute::make(
            set: fn (mixed $value): int => is_numeric($value) ? (int) $value : 0,
        );
    }
}
