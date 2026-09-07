<?php

namespace Tests\Feature;

use App\Models\FootballMatch;
use App\Models\Setting;
use App\Models\StandingRow;
use App\Support\Cyrillic;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FsnSyncTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Setting::set('club_short_name', 'Železničar');
        Setting::set('stadium', 'Stadion Železničar, Niš');
    }

    private function fakeFsn(string $html): void
    {
        Http::fake(['fsn.org.rs/*' => Http::response($html)]);
    }

    public function test_sync_imports_real_fsn_page_and_replaces_demo_table(): void
    {
        $this->fakeFsn(file_get_contents(base_path('tests/Fixtures/fsn-druga-niska-liga.html')));

        StandingRow::create(['competition' => 'first', 'team' => 'Timok Zaječar', 'position' => 1]);
        StandingRow::create(['competition' => 'first', 'team' => 'Železničar', 'position' => 2, 'played' => 4, 'form' => 'WDWL']);

        $this->artisan('fsn:sync')->assertSuccessful();

        $table = StandingRow::table()->get();
        $this->assertCount(11, $table);
        $this->assertDatabaseMissing('standing_rows', ['team' => 'Timok Zaječar']);
        $this->assertSame('Jasenovik 2018', $table[0]->team);
        $this->assertSame('OFU Broj 6 2026', $table[6]->team);

        $ours = $table->firstWhere('team', 'Železničar');
        $this->assertTrue($ours->is_club);
        $this->assertSame(3, $ours->position);
        $this->assertSame(0, $ours->played);
        $this->assertNull($ours->form);

        $this->assertSame(2, FootballMatch::count());

        $home = FootballMatch::firstTeam()->where('round', '1. kolo')->firstOrFail();
        $this->assertTrue($home->is_home);
        $this->assertSame('Supovac', $home->opponent);
        $this->assertSame('2026-09-06 11:00', $home->kickoff_at->format('Y-m-d H:i'));
        $this->assertSame('scheduled', $home->status);
        $this->assertSame('Druga niška liga', $home->competition);
        $this->assertSame('Stadion Železničar, Niš', $home->venue);

        $away = FootballMatch::firstTeam()->where('round', '2. kolo')->firstOrFail();
        $this->assertFalse($away->is_home);
        $this->assertSame('Mladost 2025', $away->opponent);
        $this->assertSame('2026-09-13 16:00', $away->kickoff_at->format('Y-m-d H:i'));
        $this->assertNull($away->venue);

        $this->assertSame('Druga niška liga', Setting::get('league_name'));

        // 11 teams → 22 rounds → the GET page plus one POST for every pair of rounds.
        Http::assertSentCount(12);
        Http::assertSent(fn ($request) => $request->method() === 'POST' && $request['round'] === 1);
    }

    public function test_sync_stores_played_results_and_skips_bye_and_unscheduled_rows(): void
    {
        $this->fakeFsn($this->snippet());

        $this->artisan('fsn:sync', ['--rounds' => 1])->assertSuccessful();

        $table = StandingRow::table()->get();
        $this->assertSame(['Supovac', 'Železničar'], $table->pluck('team')->all());
        $this->assertSame(2, $table[1]->position);
        $this->assertSame(3, $table[1]->points);
        $this->assertSame(2, $table[0]->goals_against);
        $this->assertTrue($table[1]->is_club);

        // Round 2 has no date yet, the bye row is not a match.
        $this->assertSame(1, FootballMatch::count());

        $won = FootballMatch::firstTeam()->firstOrFail();
        $this->assertSame('1. kolo', $won->round);
        $this->assertSame([2, 1], [$won->our_score, $won->their_score]);
        $this->assertSame('finished', $won->status);
        $this->assertSame('W', $won->outcome);
    }

    public function test_resync_keeps_manual_status_until_a_score_is_published(): void
    {
        $this->fakeFsn(file_get_contents(base_path('tests/Fixtures/fsn-druga-niska-liga.html')));

        $this->artisan('fsn:sync')->assertSuccessful();
        FootballMatch::where('round', '1. kolo')->update(['status' => 'postponed', 'venue' => 'Pomoćni teren']);

        $this->artisan('fsn:sync')->assertSuccessful();

        $this->assertSame(2, FootballMatch::count());
        $match = FootballMatch::where('round', '1. kolo')->firstOrFail();
        $this->assertSame('postponed', $match->status);
        $this->assertSame('Pomoćni teren', $match->venue);
    }

    public function test_sync_fails_loudly_when_the_page_has_no_table(): void
    {
        $this->fakeFsn('<html><body><p>Održavanje</p></body></html>');

        $this->artisan('fsn:sync')->assertFailed();
        $this->assertSame(0, StandingRow::count());
    }

    public function test_cyrillic_transliteration(): void
    {
        $this->assertSame('OFU Broj 6 2026', Cyrillic::toLatin('ОФУ Број 6 2026'));
        $this->assertSame('Mladost DK', Cyrillic::toLatin('Младост ДК'));
        $this->assertSame('Ljubić, LJUBIĆ, Džon, Đorđe Čučuk', Cyrillic::toLatin('Љубић, ЉУБИЋ, Џон, Ђорђе Чучук'));
    }

    /** A minimal page in the FSN markup: a two-team table, a played round and an unscheduled one. */
    private function snippet(): string
    {
        return <<<'HTML'
        <html><body>
        <table id="tabelasredina"><tr><td>&nbsp;Суповац</td><td>1</td><td>0</td><td>0</td><td>1</td><td>1</td><td>2</td><td>-1</td><td>0</td></tr></table>
        <table id="tabelasredina"><tr><td>&nbsp;Железничар</td><td>1</td><td>1</td><td>0</td><td>0</td><td>2</td><td>1</td><td>1</td><td>3</td></tr></table>
        <table><tr><td>Коло бр: 1</td><td>Резултати</td><td>&nbsp;05/06.09.2026.</td></tr></table>
        <table id="datum">
          <tr id="rez"><td>&nbsp; </td><td>,&nbsp;</td><td>Мезграја</td><td id="rezkolona"></td><td>слободан</td><td>&nbsp;</td></tr>
          <tr id="rez1"><td>&nbsp;06.09.2026 </td><td>,&nbsp;11:00</td><td>Железничар</td><td id="rezkolona">2:1</td><td>Суповац</td><td>&nbsp;</td></tr>
        </table>
        <table><tr><td>Коло бр: 2</td><td>Резултати</td><td>&nbsp;</td></tr></table>
        <table id="datum">
          <tr id="rez1"><td>&nbsp; </td><td>,&nbsp;</td><td>Младост 2025</td><td id="rezkolona"></td><td>Железничар</td><td>&nbsp;</td></tr>
        </table>
        </body></html>
        HTML;
    }
}
