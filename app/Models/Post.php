<?php

namespace App\Models;

use App\Support\VideoEmbed;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Post extends Model
{
    protected $fillable = [
        'title', 'slug', 'excerpt', 'body', 'category_id', 'user_id', 'author_name',
        'status', 'published_at', 'featured_image', 'video_url', 'show_on_home', 'is_pinned',
        'seo_title', 'seo_description',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'show_on_home' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        $clear = function (): void {
            Cache::forget('site_latest_posts');
            Cache::forget('site_ticker');
        };
        static::saved($clear);
        static::deleted($clear);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function getAuthorDisplayAttribute(): ?string
    {
        return $this->author?->name ?? $this->author_name;
    }

    public function getReadingTimeAttribute(): int
    {
        return max(1, (int) ceil(Str::wordCount(strip_tags($this->body)) / 200));
    }

    /** YouTube/Vimeo URL converted to an embeddable iframe src, or null. */
    public function getVideoEmbedUrlAttribute(): ?string
    {
        return VideoEmbed::url($this->video_url);
    }
}
