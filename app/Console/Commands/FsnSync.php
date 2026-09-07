<?php

namespace App\Console\Commands;

use App\Models\FootballMatch;
use App\Models\Setting;
use App\Models\StandingRow;
use App\Services\FsnLeagueParser;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

/**
 * Pulls the league table and the first team's fixtures/results from the
 * Fudbalski savez Niša site (config: site.fsn). Safe to re-run: rows are
 * upserted, teams that left the table are removed, statuses set by hand in
 * the admin (e.g. "odložena") survive until FSN publishes a score.
 */
class FsnSync extends Command
{
    protected $signature = 'fsn:sync
        {--url= : Stranica lige na fsn.org.rs (podrazumevano: config site.fsn.url)}
        {--rounds= : Broj kola koja se povlače (podrazumevano: izračunato iz broja timova)}
        {--dry-run : Samo prikaži šta bi bilo upisano}';

    protected $description = 'Povlači tabelu i raspored prvog tima sa sajta Fudbalskog saveza Niša';

    public function handle(FsnLeagueParser $parser): int
    {
        $url = $this->option('url') ?: config('site.fsn.url');
        $league = config('site.fsn.league_name');
        $club = Setting::get('club_short_name', config('site.short_name'));

        $page = $parser->parse($this->fetch($url));

        if ($page['standings'] === []) {
            $this->error("Na {$url} nema tabele — struktura stranice se verovatno promenila.");

            return self::FAILURE;
        }

        $teams = count($page['standings']);
        // Double round-robin; an odd number of teams means one bye slot per round.
        $rounds = (int) ($this->option('rounds') ?: ($teams + $teams % 2 - 1) * 2);

        $matches = collect($page['matches']);
        for ($round = 1; $round <= $rounds; $round += 2) { // every page shows two rounds
            $matches = $matches->merge($parser->parse($this->fetch($url, $round))['matches']);
        }
        $matches = $matches->unique(fn (array $m) => "{$m['round']}|{$m['home']}|{$m['away']}");

        $ours = $matches
            ->filter(fn (array $m) => $this->isClub($m['home'], $club) || $this->isClub($m['away'], $club))
            ->sortBy('round')
            ->values();

        $this->line("Tabela: {$teams} timova · kola na sajtu: {$matches->pluck('round')->unique()->count()} · utakmice {$club}: {$ours->count()}");

        if ($ours->isEmpty()) {
            $this->warn("Nijedna utakmica nije prepoznata kao naša — proveri da se „club_short_name“ ({$club}) poklapa sa imenom kluba na FSN sajtu.");
        }

        if ($this->option('dry-run')) {
            $this->table(
                ['Kolo', 'Datum', 'Vreme', 'Domaćin', 'Rez.', 'Gost'],
                $ours->map(fn (array $m) => [
                    $m['round'], $m['date'], $m['time'], $m['home'],
                    $m['home_score'] !== null ? "{$m['home_score']}:{$m['away_score']}" : '',
                    $m['away'],
                ]),
            );

            return self::SUCCESS;
        }

        $this->syncStandings($page['standings'], $club);
        $skipped = $this->syncMatches($ours, $league, $club);
        Setting::set('league_name', $league);

        $this->info("Upisano: tabela ({$teams} timova) i ".($ours->count() - $skipped).' utakmica.'
            .($skipped ? " Preskočeno jer još nemaju datum: {$skipped}." : ''));

        return self::SUCCESS;
    }

    private function fetch(string $url, ?int $round = null): string
    {
        $request = Http::withUserAgent('FK Zeleznicar Nis site (fsn:sync)')->timeout(20)->retry(2, 500);

        $response = $round === null
            ? $request->get($url)
            : $request->asForm()->post($url, ['round' => $round]);

        return $response->throw()->body();
    }

    private function isClub(string $team, string $club): bool
    {
        return mb_strtolower($team) === mb_strtolower($club);
    }

    /** @param list<array<string, int|string>> $rows */
    private function syncStandings(array $rows, string $club): void
    {
        $competition = config('site.fsn.competition');

        foreach ($rows as $row) {
            StandingRow::updateOrCreate(
                ['competition' => $competition, 'team' => $row['team']],
                [...$row, 'form' => null, 'is_club' => $this->isClub($row['team'], $club)], // FSN publishes no form column
            );
        }

        StandingRow::where('competition', $competition)
            ->whereNotIn('team', array_column($rows, 'team'))
            ->delete();
    }

    /** @return int number of fixtures skipped because FSN has not scheduled them yet */
    private function syncMatches(Collection $ours, string $league, string $club): int
    {
        $skipped = 0;

        foreach ($ours as $m) {
            if ($m['date'] === null) {
                $skipped++;

                continue;
            }

            $isHome = $this->isClub($m['home'], $club);

            $match = FootballMatch::firstOrNew([
                'team_type' => 'first',
                'competition' => $league,
                'round' => "{$m['round']}. kolo",
            ]);

            $match->fill([
                'kickoff_at' => Carbon::createFromFormat('d.m.Y H:i', $m['date'].' '.($m['time'] ?? '00:00')),
                'opponent' => $isHome ? $m['away'] : $m['home'],
                'is_home' => $isHome,
                'our_score' => $isHome ? $m['home_score'] : $m['away_score'],
                'their_score' => $isHome ? $m['away_score'] : $m['home_score'],
            ]);

            if ($m['home_score'] !== null) {
                $match->status = 'finished';
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
