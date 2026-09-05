<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\FootballMatch;
use App\Models\Player;
use App\Models\Post;
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
            ['U-7 · Škola fudbala', '2019/2020', 'Prvi koraci: igra, lopta i druženje. Bez rezultata, sa puno smeha.', 'Uto, Čet · 17:00–18:00'],
            ['U-9', '2017/2018', 'Osnove tehnike kroz igru. Prvi turniri i prve utakmice u malom formatu.', 'Pon, Sre, Pet · 17:00–18:15'],
            ['U-11', '2015/2016', 'Rad na tehnici i koordinaciji, razumevanje pozicija, liga 7+1.', 'Pon, Sre, Pet · 18:15–19:30'],
            ['U-13 · Petlići', '2013/2014', 'Prelazak na veliki teren, taktičke osnove i takmičenje u ligi petlića.', 'Uto, Čet, Sub · 17:30–19:00'],
            ['U-15 · Pioniri', '2011/2012', 'Pionirska liga, individualni plan razvoja i priprema za kadetski uzrast.', 'Pon, Sre, Pet · 18:30–20:00'],
            ['U-17 · Kadeti', '2009/2010', 'Kadetska liga. Ozbiljan trenažni proces i rad sa prvim timom.', 'Uto, Čet, Sub · 19:00–20:30'],
            ['U-19 · Omladinci', '2007/2008', 'Poslednji korak pred prvi tim. Najbolji već treniraju sa seniorima.', 'Pon, Sre, Pet · 19:30–21:00'],
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
        $rows = [
            // number, name, position, born, height, academy?
            [1, 'Nikola Stanković', 'GK', '2001-03-14', 191, true],
            [12, 'Luka Petrović', 'GK', '2005-08-02', 188, true],
            [2, 'Miloš Jovanović', 'DF', '1998-11-21', 183, false],
            [4, 'Stefan Ilić', 'DF', '2000-05-09', 186, true],
            [5, 'Đorđe Mitić', 'DF', '1996-01-30', 189, false],
            [3, 'Aleksa Đorđević', 'DF', '2003-09-17', 178, true],
            [15, 'Vuk Ristić', 'DF', '2004-04-25', 181, true],
            [6, 'Marko Nikolić', 'MF', '1997-07-12', 180, false],
            [8, 'Filip Stojanović', 'MF', '1999-02-03', 177, true],
            [10, 'Lazar Cvetković', 'MF', '1995-10-08', 175, false],
            [14, 'Ognjen Pavlović', 'MF', '2002-12-19', 179, true],
            [18, 'Andrej Živković', 'MF', '2005-06-11', 174, true],
            [20, 'Petar Milošević', 'MF', '2003-03-27', 182, false],
            [7, 'Uroš Stevanović', 'FW', '2000-08-15', 184, true],
            [9, 'Dušan Kostić', 'FW', '1994-04-04', 187, false],
            [11, 'Nemanja Živadinović', 'FW', '2002-01-22', 176, true],
            [17, 'Veljko Todorović', 'FW', '2006-09-30', 180, true],
        ];

        foreach ($rows as $i => [$number, $name, $pos, $born, $height, $academy]) {
            Player::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'shirt_number' => $number,
                    'position' => $pos,
                    'nationality' => 'Srbija',
                    'birth_date' => $born,
                    'height_cm' => $height,
                    'from_academy' => $academy,
                    'is_captain' => $number === 10,
                    'sort_order' => $i,
                ],
            );
        }
    }

    private function staff(): void
    {
        $selections = YouthSelection::query()->pluck('id', 'slug');

        $rows = [
            ['Dragan Mladenović', 'Šef stručnog štaba', 'first_team', null, 'UEFA A'],
            ['Ivan Ćirić', 'Pomoćni trener', 'first_team', null, 'UEFA B'],
            ['Bojan Ranđelović', 'Trener golmana', 'first_team', null, 'UEFA GK B'],
            ['Saša Stamenković', 'Direktor omladinske škole', 'youth', null, 'UEFA A'],
            ['Milan Đokić', 'Trener', 'youth', 'u-19-omladinci', 'UEFA B'],
            ['Nenad Krstić', 'Trener', 'youth', 'u-17-kadeti', 'UEFA B'],
            ['Jelena Pešić', 'Trener', 'youth', 'u-9', 'UEFA C'],
            ['Zoran Antić', 'Trener', 'youth', 'u-7-skola-fudbala', 'UEFA C'],
            ['Predrag Marinković', 'Predsednik kluba', 'club', null, null],
        ];

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
        $league = 'Zona Istok';
        $saturday = now()->startOfWeek()->addDays(5)->setTime(16, 30);

        $rows = [
            // days offset from this Saturday, opponent, home?, our, their, status, round
            [-28, 'Radnički Pirot', true, 2, 1, 'finished', '1. kolo'],
            [-21, 'Dubočica', false, 0, 0, 'finished', '2. kolo'],
            [-14, 'Sinđelić Niš', true, 3, 0, 'finished', '3. kolo'],
            [-7, 'Car Konstantin', false, 1, 2, 'finished', '4. kolo'],
            [0, 'Timok Zaječar', true, null, null, 'scheduled', '5. kolo'],
            [7, 'Jedinstvo Bela Palanka', false, null, null, 'scheduled', '6. kolo'],
            [14, 'Radan Lebane', true, null, null, 'scheduled', '7. kolo'],
            [21, 'Moravac Mrštane', false, null, null, 'scheduled', '8. kolo'],
        ];

        foreach ($rows as [$offset, $opponent, $home, $our, $their, $status, $round]) {
            $kickoff = $saturday->copy()->addDays($offset);

            FootballMatch::updateOrCreate(
                ['team_type' => 'first', 'opponent' => $opponent, 'competition' => $league],
                [
                    'round' => $round,
                    'kickoff_at' => $kickoff,
                    'is_home' => $home,
                    'our_score' => $our,
                    'their_score' => $their,
                    'venue' => $home ? 'Stadion Železničar, Niš' : null,
                    'status' => $status,
                ],
            );
        }
    }

    private function standings(): void
    {
        $rows = [
            ['Sinđelić Niš', 4, 3, 1, 0, 9, 3, 'WWDW'],
            ['Železničar', 4, 2, 1, 1, 6, 3, 'WDWL'],
            ['Timok Zaječar', 4, 2, 1, 1, 5, 4, 'DWLW'],
            ['Car Konstantin', 4, 2, 0, 2, 6, 6, 'LWLW'],
            ['Dubočica', 4, 1, 3, 0, 4, 3, 'DDWD'],
            ['Radnički Pirot', 4, 1, 2, 1, 5, 5, 'LDDW'],
            ['Radan Lebane', 4, 1, 1, 2, 3, 5, 'WLLD'],
            ['Jedinstvo Bela Palanka', 4, 1, 1, 2, 2, 4, 'LDWL'],
            ['Moravac Mrštane', 4, 0, 2, 2, 2, 6, 'DLDL'],
            ['Hajduk Veljko', 4, 0, 2, 2, 1, 4, 'LDLD'],
        ];

        foreach ($rows as $i => [$team, $p, $w, $d, $l, $gf, $ga, $form]) {
            StandingRow::updateOrCreate(
                ['competition' => 'first', 'team' => $team],
                [
                    'position' => $i + 1,
                    'played' => $p, 'won' => $w, 'drawn' => $d, 'lost' => $l,
                    'goals_for' => $gf, 'goals_against' => $ga,
                    'points' => $w * 3 + $d,
                    'form' => $form,
                    'is_club' => $team === 'Železničar',
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
                'title' => 'Železničar ubedljiv protiv Sinđelića: 3:0 pred punim tribinama',
                'category' => 'Utakmice',
                'days' => 2,
                'home' => true,
                'video' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'excerpt' => 'Gradski derbi pripao je Železničaru. Dva gola iz prekida i jedan iz kontre za najubedljiviju pobedu sezone.',
                'paras' => [
                    'Pred više od hiljadu gledalaca, Železničar je od prvog minuta preuzeo inicijativu. Vođstvo je stiglo već u 12. minutu nakon kornera, a do poluvremena je Kostić udvostručio prednost.',
                    'U nastavku je gost pokušao da se vrati, ali je odbrana domaćina, predvođena Mitićem, bila neprobojna. Tačku na utakmicu stavio je devetnaestogodišnji Todorović, još jedan igrač iz naše omladinske škole.',
                    'Pogledajte najzanimljivije trenutke sa utakmice u video prilogu.',
                ],
            ],
            [
                'title' => 'Otvoren upis za sezonu 2025/26: prvi trening je besplatan',
                'category' => 'Omladinci',
                'days' => 4,
                'home' => false,
                'excerpt' => 'Omladinska škola Železničara prima nove članove u svim uzrastima od 5 do 19 godina. Prijavite dete onlajn za manje od minuta.',
                'paras' => [
                    'Sa početkom nove sezone otvaramo vrata svim devojčicama i dečacima koji žele da igraju fudbal. Prijava je jednostavna: popunite formu na sajtu, a naši treneri će vas pozvati i dogovoriti prvi, probni trening.',
                    'Treninzi se održavaju tri puta nedeljno na terenima kluba, pod vođstvom licenciranih trenera. Oprema za prvi trening nije potrebna — dovoljne su patike i dobra volja.',
                    'Za sve dodatne informacije roditelji mogu da nas pozovu ili pošalju poruku preko sajta.',
                ],
            ],
            [
                'title' => 'Trojica omladinaca potpisala prve ugovore sa klubom',
                'category' => 'Prvi tim',
                'days' => 7,
                'excerpt' => 'Todorović, Živković i Ristić nastavljaju putovanje koje je počelo u školi fudbala Železničara.',
                'paras' => [
                    'Put od škole fudbala do prvog tima je ono zbog čega ovaj klub postoji. Trojica naših omladinaca danas su potpisala prve seniorske ugovore i od ove sezone su punopravni članovi prvog tima.',
                    'Sva trojica su u klubu od svoje sedme godine i prošla su sve selekcije omladinske škole. Čestitamo im i želimo mnogo uspešnih utakmica u našem dresu.',
                ],
            ],
            [
                'title' => 'Kadeti Železničara prvaci turnira u Leskovcu',
                'category' => 'Omladinci',
                'days' => 10,
                'video' => 'https://www.youtube.com/watch?v=aqz-KE-bpKQ',
                'excerpt' => 'Generacija 2009/2010 osvojila je prvo mesto na jakom regionalnom turniru bez ijednog poraza.',
                'paras' => [
                    'Naši kadeti su na turniru u Leskovcu odigrali pet utakmica, zabeležili četiri pobede i jedan nerešen rezultat i zasluženo podigli pehar.',
                    'Za najboljeg igrača turnira proglašen je naš kapiten, a golman Železničara primio je samo jedan gol na celom turniru.',
                ],
            ],
            [
                'title' => 'Podrži klub: svaka uplata ide u omladinsku školu',
                'category' => 'Klub',
                'days' => 14,
                'excerpt' => 'Otvorili smo jednostavan način da navijači i prijatelji kluba pomognu rad sa decom — uplatom na račun kluba.',
                'paras' => [
                    'Železničar nema bogatog vlasnika. Ima grad, navijače i ljude koji veruju u ono što radimo sa decom. Zato smo napravili stranicu „Podrži klub“ sa svim podacima za uplatu.',
                    'Sredstva idu u opremu, kotizacije za turnire i prevoz mlađih selekcija. Hvala svima koji su već uplatili.',
                ],
            ],
            [
                'title' => 'Renoviran teren sa veštačkom travom za mlađe selekcije',
                'category' => 'Klub',
                'days' => 21,
                'video' => 'https://www.youtube.com/watch?v=ScMzIvxBSi4',
                'excerpt' => 'Posle dva meseca radova, pomoćni teren je dobio novu podlogu i rasvetu. Od sada treniramo i zimi.',
                'paras' => [
                    'Novi teren sa veštačkom travom omogućava našim mlađim selekcijama da treniraju tokom cele godine, bez obzira na vremenske uslove.',
                    'Pogledajte kako je izgledala rekonstrukcija i prvi trening na novom terenu.',
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
