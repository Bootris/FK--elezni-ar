<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\FootballMatch;
use App\Models\Player;
use App\Models\Post;
use App\Models\Setting;
use App\Models\StaffMember;
use App\Models\StandingRow;
use App\Models\YouthSelection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Placeholder content so a fresh install looks like a finished club site:
 * squad, staff, youth selections, fixtures, a league table and news.
 * Names and numbers are demo values — replace them in the admin.
 * Idempotent: keyed by slug / natural keys, safe to re-run.
 */
class ZeleznicarDemoSeeder extends Seeder
{
    public function run(): void
    {
        $this->selections();
        $this->players();
        $this->staff();
        $this->matches();
        $this->standings();
        $this->posts();
    }

    private function selections(): void
    {
        $rows = [
            ['U-7 · Škola fudbala', '2020/2021', 'Prvi koraci: igra, lopta i druženje. Bez rezultata, sa puno smeha.', 'Uto, Čet · 17:00–18:00'],
            ['U-9', '2018/2019', 'Osnove tehnike kroz igru. Prvi turniri i prve utakmice u malom formatu.', 'Pon, Sre, Pet · 17:00–18:15'],
            ['U-11', '2016/2017', 'Rad na tehnici i koordinaciji, razumevanje pozicija, liga 7+1.', 'Pon, Sre, Pet · 18:15–19:30'],
            ['U-13 · Petlići', '2014/2015', 'Prelazak na veliki teren, taktičke osnove i takmičenje u ligi petlića.', 'Uto, Čet, Sub · 17:30–19:00'],
            ['U-15 · Pioniri', '2012/2013', 'Pionirska liga, individualni plan razvoja i priprema za kadetski uzrast.', 'Pon, Sre, Pet · 18:30–20:00'],
            ['U-17 · Kadeti', '2010/2011', 'Kadetska liga. Ozbiljan trenažni proces i rad sa prvim timom.', 'Uto, Čet, Sub · 19:00–20:30'],
            ['U-19 · Omladinci', '2008/2009', 'Poslednji korak pred prvi tim. Najbolji već treniraju sa seniorima.', 'Pon, Sre, Pet · 19:30–21:00'],
        ];

        foreach ($rows as $i => [$name, $years, $desc, $schedule]) {
            YouthSelection::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'birth_years' => $years,
                    'description' => $desc,
                    'training_schedule' => $schedule,
                    'training_venue' => 'Tereni FK Železničar',
                    'sort_order' => $i,
                ],
            );
        }
    }

    private function players(): void
    {
        // Prvi tim, sezona 2026/27. Potvrđeno sa zvaničnih fotografija: imena,
        // kapiten (Ignjatović) i golman (Rančić, jedini u golmanskom dresu).
        // POZICIJE OSTALIH SU PRIVREMENE — raspoređene tako da tim izgleda
        // normalno na sajtu; klub ih ispravlja u adminu, kao i brojeve dresova
        // i godišta, koja ovde namerno stoje prazna umesto izmišljena.
        $rows = [
            // name, position, captain?
            ['Aleksa Rančić', 'GK', false],
            ['Dušan Vasić', 'DF', false],
            ['Miloš Spasić', 'DF', false],
            ['Sava Mandić', 'DF', false],
            ['Valentino Čađanović', 'DF', false],
            ['Branislav Nikolić', 'DF', false],
            ['Nemanja Vidojković', 'DF', false],
            ['Nikola Rakić', 'MF', false],
            ['Pavle Zlatanović', 'MF', false],
            ['Boris Bončić', 'MF', false],
            ['Marko Kostadinović', 'MF', false],
            ['Filip Stefanović', 'MF', false],
            ['Aleksandar Ignjatović', 'MF', true],
            ['Đorđe Stevović', 'MF', false],
            ['Uroš Antonijević', 'FW', false],
            ['Đorđe Petrović', 'FW', false],
            ['Blagoje Toković', 'FW', false],
            ['Filip Nikolić', 'FW', false],
        ];

        foreach ($rows as $i => [$name, $pos, $captain]) {
            Player::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'position' => $pos,
                    'nationality' => 'Srbija',
                    'is_captain' => $captain,
                    'sort_order' => $i,
                ],
            );
        }
    }

    private function staff(): void
    {
        // Prazno namerno: stručni štab su stvarni ljudi i klub ih unosi kroz
        // admin (Stručni štab). Sajt uredno prikazuje praznu sekciju dok ih nema.
        // Oblik reda: [ime, uloga, first_team|youth|club, slug selekcije|null, licenca|null]
        $rows = [];

        $selections = YouthSelection::query()->pluck('id', 'slug');

        foreach ($rows as $i => [$name, $role, $dept, $selectionSlug, $licence]) {
            StaffMember::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'role' => $role,
                    'department' => $dept,
                    'youth_selection_id' => $selectionSlug ? $selections[$selectionSlug] ?? null : null,
                    'licence' => $licence,
                    'sort_order' => $i,
                ],
            );
        }
    }

    private function matches(): void
    {
        // Druga niška liga, kako je objavljeno na fsn.org.rs (stanje 09.09.2026).
        // Kola koja FSN još nije zakazao namerno nisu ovde — `php artisan fsn:sync`
        // ih dodaje čim dobiju datum, i upisuje ih po istom ključu (kolo).
        $league = config('site.fsn.league_name');
        $stadium = Setting::get('stadium', 'Stadion Železničar, Niš');

        $rows = [
            // round, kickoff, opponent, home?, our, their, status
            [1, '2026-09-06 11:00', 'Supovac', true, 2, 0, 'finished'],
            [2, '2026-09-13 16:00', 'Mladost 2025', false, null, null, 'scheduled'],
        ];

        foreach ($rows as [$round, $kickoff, $opponent, $home, $our, $their, $status]) {
            FootballMatch::updateOrCreate(
                ['team_type' => 'first', 'competition' => $league, 'round' => "{$round}. kolo"],
                [
                    'kickoff_at' => $kickoff,
                    'opponent' => $opponent,
                    'is_home' => $home,
                    'our_score' => $our,
                    'their_score' => $their,
                    'venue' => $home ? $stadium : null,
                    'status' => $status,
                ],
            );
        }
    }

    private function standings(): void
    {
        // Druga niška liga posle 1. kola (fsn.org.rs, 09.09.2026). FSN ne
        // objavljuje kolonu forme, pa je ostavljena prazna — isto kao `fsn:sync`.
        $rows = [
            // team, played, won, drawn, lost, goals for, goals against
            ['Jasenovik 2018', 1, 1, 0, 0, 3, 0],
            ['Spartak', 1, 1, 0, 0, 5, 3],
            ['Standard 2021', 1, 1, 0, 0, 5, 3],
            ['Železničar', 1, 1, 0, 0, 2, 0],
            ['Mladost 2025', 1, 1, 0, 0, 4, 3],
            ['Mezgraja', 0, 0, 0, 0, 0, 0],
            ['Mladost DK', 1, 0, 0, 1, 3, 4],
            ['Vrtište', 1, 0, 0, 1, 3, 5],
            ['OFU Broj 6 2026', 1, 0, 0, 1, 3, 5],
            ['Supovac', 1, 0, 0, 1, 0, 2],
            ['Omladinac', 1, 0, 0, 1, 0, 3],
        ];

        $club = Setting::get('club_short_name', config('site.short_name'));

        foreach ($rows as $i => [$team, $p, $w, $d, $l, $gf, $ga]) {
            StandingRow::updateOrCreate(
                ['competition' => config('site.fsn.competition'), 'team' => $team],
                [
                    'position' => $i + 1,
                    'played' => $p, 'won' => $w, 'drawn' => $d, 'lost' => $l,
                    'goals_for' => $gf, 'goals_against' => $ga,
                    'points' => $w * 3 + $d,
                    'form' => null,
                    'is_club' => $team === $club,
                ],
            );
        }
    }

    private function posts(): void
    {
        $cats = Category::query()->pluck('id', 'name');
        $body = fn (array $paras) => collect($paras)->map(fn ($p) => "<p>{$p}</p>")->implode("\n");

        $posts = [
            [
                'title' => 'Pobeda na startu sezone: Železničar 2:0 Supovac',
                'category' => 'Utakmice',
                'days' => 3,
                'home' => true,
                'excerpt' => 'U 1. kolu Druge niške lige Železničar je na svom terenu savladao Supovac rezultatom 2:0.',
                'paras' => [
                    'Prvo kolo Druge niške lige odigrano je 6. septembra na Stadionu Železničar u Nišu. Naš tim je pobedio Supovac rezultatom 2:0.',
                    'Sledeći protivnik je Mladost 2025, u gostima, u 2. kolu.',
                ],
            ],
            [
                'title' => 'Otvoren upis za sezonu 2026/27: prvi trening je besplatan',
                'category' => 'Omladinci',
                'days' => 4,
                'home' => false,
                'excerpt' => 'Omladinska škola Železničara prima nove članove u svim uzrastima od 5 do 19 godina. Prijavite dete onlajn za manje od minuta.',
                'paras' => [
                    'Sa početkom nove sezone otvaramo vrata svim devojčicama i dečacima koji žele da igraju fudbal. Prijava je jednostavna: popunite formu na sajtu, a naši treneri će vas pozvati i dogovoriti prvi, probni trening.',
                    'Treninzi se održavaju tri puta nedeljno na terenima kluba, pod vođstvom licenciranih trenera. Oprema za prvi trening nije potrebna, dovoljne su patike i dobra volja.',
                    'Za sve dodatne informacije roditelji mogu da nas pozovu ili pošalju poruku preko sajta.',
                ],
            ],
            [
                'title' => 'Podrži klub: svaka uplata ide u omladinsku školu',
                'category' => 'Klub',
                'days' => 14,
                'excerpt' => 'Otvorili smo jednostavan način da navijači i prijatelji kluba pomognu rad sa decom, uplatom na račun kluba.',
                'paras' => [
                    'Železničar nema bogatog vlasnika. Ima grad, navijače i ljude koji veruju u ono što radimo sa decom. Zato smo napravili stranicu „Podrži klub“ sa svim podacima za uplatu.',
                    'Sredstva idu u opremu, kotizacije za turnire i prevoz mlađih selekcija. Hvala svima koji su već uplatili.',
                ],
            ],
        ];

        foreach ($posts as $data) {
            Post::updateOrCreate(
                ['slug' => Str::slug($data['title'])],
                [
                    'title' => $data['title'],
                    'excerpt' => $data['excerpt'],
                    'body' => $body($data['paras']),
                    'category_id' => $cats[$data['category']] ?? null,
                    'author_name' => 'FK Železničar',
                    'status' => 'published',
                    'published_at' => now()->subDays($data['days'])->setTime(12, 0),
                    'video_url' => $data['video'] ?? null,
                    'show_on_home' => $data['home'] ?? false,
                ],
            );
        }
    }
}
