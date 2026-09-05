<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A fixture or result. Stored from the club's perspective (opponent, home/away,
 * our score / their score) so the admin never has to type our own name.
 */
class FootballMatch extends Model
{
    protected $table = 'matches';

    public const STATUSES = [
        'scheduled' => 'Zakazana',
        'live' => 'U toku',
        'finished' => 'Odigrana',
        'postponed' => 'Odložena',
    ];

    protected $fillable = [
        'team_type', 'youth_selection_id', 'competition', 'round', 'kickoff_at', 'opponent',
        'is_home', 'our_score', 'their_score', 'venue', 'status', 'post_id', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'kickoff_at' => 'datetime',
            'is_home' => 'boolean',
        ];
    }

    public function selection(): BelongsTo
    {
        return $this->belongsTo(YouthSelection::class, 'youth_selection_id');
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function scopeFirstTeam(Builder $query): Builder
    {
        return $query->where('team_type', 'first');
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->whereIn('status', ['scheduled', 'live'])
            ->where('kickoff_at', '>=', now()->subHours(3))
            ->orderBy('kickoff_at');
    }

    public function scopeFinished(Builder $query): Builder
    {
        return $query->where('status', 'finished')->orderByDesc('kickoff_at');
    }

    public function getIsFinishedAttribute(): bool
    {
        return $this->status === 'finished' && $this->our_score !== null && $this->their_score !== null;
    }

    /** W / D / L from the club's perspective, or null when not played. */
    public function getOutcomeAttribute(): ?string
    {
        if (! $this->is_finished) {
            return null;
        }

        return match (true) {
            $this->our_score > $this->their_score => 'W',
            $this->our_score < $this->their_score => 'L',
            default => 'D',
        };
    }

    public function getHomeTeamAttribute(): string
    {
        return $this->is_home ? $this->clubName() : $this->opponent;
    }

    public function getAwayTeamAttribute(): string
    {
        return $this->is_home ? $this->opponent : $this->clubName();
    }

    public function getHomeScoreAttribute(): ?int
    {
        return $this->is_home ? $this->our_score : $this->their_score;
    }

    public function getAwayScoreAttribute(): ?int
    {
        return $this->is_home ? $this->their_score : $this->our_score;
    }

    public function getScoreLineAttribute(): ?string
    {
        return $this->is_finished ? "{$this->home_score}:{$this->away_score}" : null;
    }

    private function clubName(): string
    {
        return Setting::get('club_short_name', 'Železničar');
    }
}
