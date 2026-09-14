<?php

namespace App\Services;

use App\Models\FootballMatch;
use App\Models\Setting;
use App\Models\StandingRow;
use Illuminate\Support\Carbon;

/**
 * Writes a parsed league page (standings + fixtures) into the database. Shared by
 * every source (`fsn:sync`, `srbijasport:sync`) so all of them behave the same:
 * rows are upserted, teams that left the table are removed, matches are keyed by
 * round, and a status set by hand in the admin (e.g. "odložena") survives until
 * the source publishes a score.
 */
class LeagueSync
{
    /** A score that appeared this soon after kick-off may still be changing. */
    private const MATCH_DURATION_MINUTES = 120;

    public function __construct(private readonly string $club) {}

    public static function forClub(): self
    {
        return new self(Setting::get('club_short_name', config('site.short_name')));
    }

    public function club(): string
    {
        return $this->club;
    }

    public function isClub(string $team): bool
    {
        return mb_strtolower(trim($team)) === mb_strtolower($this->club);
    }

    /** @param array{home:string,away:string} $match */
    public function isOurs(array $match): bool
    {
        return $this->isClub($match['home']) || $this->isClub($match['away']);
    }

    /** @param list<array{position:int,team:string,played:int,won:int,drawn:int,lost:int,goals_for:int,goals_against:int,points:int}> $rows */
    public function standings(array $rows, string $competition = 'first'): void
    {
        foreach ($rows as $row) {
            StandingRow::updateOrCreate(
                ['competition' => $competition, 'team' => $row['team']],
                [...$row, 'form' => null, 'is_club' => $this->isClub($row['team'])], // neither source publishes a form column
            );
        }

        StandingRow::where('competition', $competition)
            ->whereNotIn('team', array_column($rows, 'team'))
            ->delete();
    }

    /**
     * @param  iterable<array{round:int,date:?string,time:?string,home:string,away:string,home_score:?int,away_score:?int}>  $ours
     * @return int number of fixtures skipped because the source has not scheduled them yet
     */
    public function matches(iterable $ours, string $league, bool $liveAware = false): int
    {
        $skipped = 0;

        foreach ($ours as $m) {
            if ($m['date'] === null) {
                $skipped++;

                continue;
            }

            $isHome = $this->isClub($m['home']);
            $kickoff = Carbon::createFromFormat('d.m.Y H:i', $m['date'].' '.($m['time'] ?? '00:00'));

            $match = FootballMatch::firstOrNew([
                'team_type' => 'first',
                'competition' => $league,
                'round' => "{$m['round']}. kolo",
            ]);

            $match->fill([
                'kickoff_at' => $kickoff,
                'opponent' => $isHome ? $m['away'] : $m['home'],
                'is_home' => $isHome,
                'our_score' => $isHome ? $m['home_score'] : $m['away_score'],
                'their_score' => $isHome ? $m['away_score'] : $m['home_score'],
            ]);

            if ($m['home_score'] !== null) {
                $inProgress = $liveAware && $kickoff->diffInMinutes(now(), false) < self::MATCH_DURATION_MINUTES;
                $match->status = $inProgress ? 'live' : 'finished';
            } elseif ($match->status === 'live') {
                $match->status = 'scheduled'; // the source withdrew the score
            } elseif (! $match->exists) {
                $match->status = 'scheduled';
            }

            if (! $match->exists) {
                $match->venue = $isHome ? Setting::get('stadium') : null;
            }

            $match->save();
        }

        return $skipped;
    }
}
