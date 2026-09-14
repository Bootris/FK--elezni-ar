<?php

namespace App\Console\Commands;

use App\Models\Setting;
use App\Services\FsnLeagueParser;
use App\Services\LeagueSync;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Pulls the league table and the first team's whole fixture list from the
 * Fudbalski savez Niša site (config: site.fsn). Safe to re-run — see LeagueSync
 * for the upsert rules. For results the same evening see `srbijasport:sync`.
 */
class FsnSync extends Command
{
    protected $signature = 'fsn:sync
        {--url= : Stranica lige na fsn.org.rs (podrazumevano: config site.fsn.url)}
        {--file= : Umesto preuzimanja, parsiraj sačuvanu HTML stranicu sa diska}
        {--rounds= : Broj kola koja se povlače (podrazumevano: izračunato iz broja timova)}
        {--dry-run : Samo prikaži šta bi bilo upisano}';

    protected $description = 'Povlači tabelu i raspored prvog tima sa sajta Fudbalskog saveza Niša';

    public function handle(FsnLeagueParser $parser): int
    {
        $url = $this->option('url') ?: config('site.fsn.url');
        $file = $this->option('file');
        $league = config('site.fsn.league_name');
        $sync = LeagueSync::forClub();
        $club = $sync->club();

        $source = $file ? $this->read($file) : $this->fetch($url);
        $page = $parser->parse($source);

        if ($page['standings'] === []) {
            $this->error('Na '.($file ?: $url).' nema tabele — struktura stranice se verovatno promenila.');

            return self::FAILURE;
        }

        $teams = count($page['standings']);
        // Double round-robin; an odd number of teams means one bye slot per round.
        $rounds = (int) ($this->option('rounds') ?: ($teams + $teams % 2 - 1) * 2);

        $matches = collect($page['matches']);

        // A saved page is one snapshot — there is no server to post further rounds to.
        if (! $file) {
            for ($round = 1; $round <= $rounds; $round += 2) { // every page shows two rounds
                $matches = $matches->merge($parser->parse($this->fetch($url, $round))['matches']);
            }
        }
        $matches = $matches->unique(fn (array $m) => "{$m['round']}|{$m['home']}|{$m['away']}");

        $ours = $matches
            ->filter(fn (array $m) => $sync->isOurs($m))
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

        $sync->standings($page['standings'], config('site.fsn.competition'));
        $skipped = $sync->matches($ours, $league);
        Setting::set('league_name', $league);

        $this->info("Upisano: tabela ({$teams} timova) i ".($ours->count() - $skipped).' utakmica.'
            .($skipped ? " Preskočeno jer još nemaju datum: {$skipped}." : ''));

        return self::SUCCESS;
    }

    /** A page saved from the browser — for a one-off import, or when the server cannot reach the source. */
    private function read(string $path): string
    {
        if (! is_file($path)) {
            throw new RuntimeException("Fajl ne postoji: {$path}");
        }

        return (string) file_get_contents($path);
    }

    private function fetch(string $url, ?int $round = null): string
    {
        $request = Http::withUserAgent('FK Zeleznicar Nis site (fsn:sync)')->timeout(20)->retry(2, 500);

        $response = $round === null
            ? $request->get($url)
            : $request->asForm()->post($url, ['round' => $round]);

        return $response->throw()->body();
    }
}
