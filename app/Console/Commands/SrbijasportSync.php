<?php

namespace App\Console\Commands;

use App\Models\Setting;
use App\Services\LeagueSync;
use App\Services\SrbijasportLeagueParser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Pulls the league table and the current round's results from srbijasport.net
 * (config: site.srbijasport). The page shows one round at a time, so this is the
 * fast "live results" feed run every few minutes; `fsn:sync` still imports the
 * whole fixture list once a week.
 */
class SrbijasportSync extends Command
{
    protected $signature = 'srbijasport:sync
        {--url= : Stranica lige na srbijasport.net (podrazumevano: config site.srbijasport.url)}
        {--file= : Umesto preuzimanja, parsiraj sačuvanu HTML stranicu sa diska}
        {--dry-run : Samo prikaži šta bi bilo upisano}';

    protected $description = 'Povlači tabelu i rezultate tekućeg kola sa srbijasport.net';

    public function handle(SrbijasportLeagueParser $parser): int
    {
        $url = $this->option('url') ?: config('site.srbijasport.url');
        $file = $this->option('file');
        $league = config('site.srbijasport.league_name');
        $sync = LeagueSync::forClub();

        $page = $parser->parse($file ? $this->read($file) : $this->fetch($url));

        if ($page['standings'] === []) {
            $this->error('Na '.($file ?: $url).' nema tabele — struktura stranice se verovatno promenila.');

            return self::FAILURE;
        }

        if ($page['round'] === null) {
            $this->warn('Broj kola nije prepoznat — upisujem samo tabelu.');
        }

        $ours = collect($page['matches'])->filter(fn (array $m) => $sync->isOurs($m))->values();
        $teams = count($page['standings']);

        $this->line("Tabela: {$teams} timova · kolo: ".($page['round'] ?? '?').' · utakmice u kolu: '.count($page['matches'])." · naše: {$ours->count()}");

        if ($page['matches'] !== [] && $ours->isEmpty()) {
            $this->warn("Nijedna utakmica u ovom kolu nije naša — proveri da se „club_short_name“ ({$sync->club()}) poklapa sa imenom kluba na srbijasport.net.");
        }

        if ($this->option('dry-run')) {
            $this->table(
                ['Poz', 'Tim', 'UT', 'Bod'],
                array_map(fn (array $r) => [$r['position'], $r['team'], $r['played'], $r['points']], $page['standings']),
            );
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

        $sync->standings($page['standings'], config('site.srbijasport.competition'));
        $skipped = $sync->matches($ours, $league, liveAware: true);
        Setting::set('league_name', $league);

        $this->info("Upisano: tabela ({$teams} timova) i ".($ours->count() - $skipped).' utakmica.'
            .($skipped ? " Preskočeno jer još nemaju datum: {$skipped}." : ''));

        return self::SUCCESS;
    }

    private function read(string $path): string
    {
        if (! is_file($path)) {
            throw new RuntimeException("Fajl ne postoji: {$path}");
        }

        return (string) file_get_contents($path);
    }

    private function fetch(string $url): string
    {
        return Http::withUserAgent('FK Zeleznicar Nis site (srbijasport:sync)')
            ->timeout(20)
            ->retry(2, 500)
            ->get($url)
            ->throw()
            ->body();
    }
}
