<?php

namespace Tests\Feature;

use App\Models\FootballMatch;
use App\Models\Setting;
use App\Models\StandingRow;
use App\Services\SrbijasportLeagueParser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SrbijasportSyncTest extends TestCase
{
    use RefreshDatabase;

    private const FIXTURE = 'tests/Fixtures/srbijasport-druga-niska-liga.html';

    protected function setUp(): void
    {
        parent::setUp();

        Setting::set('club_short_name', 'Železničar');
        Setting::set('stadium', 'Stadion Železničar, Niš');
    }

    private function fakeSrbijasport(string $html): void
    {
        Http::fake(['srbijasport.net/*' => Http::response($html)]);
    }

    public function test_parser_reads_the_real_page(): void
    {
        $page = (new SrbijasportLeagueParser)->parse(file_get_contents(base_path(self::FIXTURE)));

        $this->assertSame(2, $page['round']);
        $this->assertCount(11, $page['standings']);
        $this->assertSame('Jasenovik 2018', $page['standings'][0]['team']); // team name only, never the city
        $this->assertSame('OFU Broj 6 2026', $page['standings'][10]['team']);

        $ours = $page['standings'][5];
        $this->assertSame(['Železničar', 6, 2, 1, 0, 1, 4, 4, 3], [
            $ours['team'], $ours['position'], $ours['played'], $ours['won'], $ours['drawn'], $ours['lost'],
            $ours['goals_for'], $ours['goals_against'], $ours['points'],
        ]);

        $this->assertCount(5, $page['matches']); // the bye ("slobodan je tim") is not a match
        $this->assertSame([
            'round' => 2, 'date' => '13.09.2026', 'time' => '16:00',
            'home' => 'Mladost 2025', 'away' => 'Železničar', 'home_score' => 4, 'away_score' => 2,
        ], $page['matches'][0]);
    }

    public function test_sync_imports_table_and_our_result_of_the_current_round(): void
    {
        $this->fakeSrbijasport(file_get_contents(base_path(self::FIXTURE)));
        Carbon::setTestNow('2026-09-14 16:00');

        StandingRow::create(['competition' => 'first', 'team' => 'Timok Zaječar', 'position' => 1]);

        $this->artisan('srbijasport:sync')->assertSuccessful();

        $table = StandingRow::table()->get();
        $this->assertCount(11, $table);
        $this->assertDatabaseMissing('standing_rows', ['team' => 'Timok Zaječar']);
        $this->assertSame('Jasenovik 2018', $table[0]->team);
        $this->assertTrue($table->firstWhere('team', 'Železničar')->is_club);

        $this->assertSame(1, FootballMatch::count());
        $match = FootballMatch::firstTeam()->firstOrFail();
        $this->assertSame('2. kolo', $match->round);
        $this->assertFalse($match->is_home);
        $this->assertSame('Mladost 2025', $match->opponent);
        $this->assertSame([2, 4], [$match->our_score, $match->their_score]);
        $this->assertSame('finished', $match->status);
        $this->assertSame('L', $match->outcome);
        $this->assertSame('2026-09-13 16:00', $match->kickoff_at->format('Y-m-d H:i'));
        $this->assertNull($match->venue);
        $this->assertSame('Druga niška liga', Setting::get('league_name'));

        Http::assertSentCount(1);
    }

    public function test_sync_updates_the_fixture_fsn_created_instead_of_duplicating_it(): void
    {
        $this->fakeSrbijasport(file_get_contents(base_path(self::FIXTURE)));

        FootballMatch::create([
            'team_type' => 'first', 'competition' => 'Druga niška liga', 'round' => '2. kolo',
            'kickoff_at' => '2026-09-13 16:00', 'opponent' => 'Mladost 2025', 'is_home' => false, 'status' => 'scheduled',
        ]);

        $this->artisan('srbijasport:sync')->assertSuccessful();

        $this->assertSame(1, FootballMatch::count());
        $this->assertSame('finished', FootballMatch::first()->status);
    }

    public function test_a_score_published_during_the_match_shows_as_live(): void
    {
        $this->fakeSrbijasport(file_get_contents(base_path(self::FIXTURE)));
        Carbon::setTestNow('2026-09-13 17:05'); // 65 minutes after kick-off

        $this->artisan('srbijasport:sync')->assertSuccessful();
        $this->assertSame('live', FootballMatch::first()->status);

        Carbon::setTestNow('2026-09-13 18:30');
        $this->artisan('srbijasport:sync')->assertSuccessful();
        $this->assertSame('finished', FootballMatch::first()->status);
    }

    public function test_unplayed_round_keeps_manual_status_and_bye_is_ignored(): void
    {
        $this->fakeSrbijasport($this->snippet(round: 3, homeScore: '', awayScore: ''));

        FootballMatch::create([
            'team_type' => 'first', 'competition' => 'Druga niška liga', 'round' => '3. kolo',
            'kickoff_at' => '2026-09-20 16:00', 'opponent' => 'Supovac', 'is_home' => true, 'status' => 'postponed',
        ]);

        $this->artisan('srbijasport:sync')->assertSuccessful();

        $match = FootballMatch::firstOrFail();
        $this->assertSame('postponed', $match->status);
        $this->assertNull($match->our_score);
        $this->assertSame(1, FootballMatch::count());
    }

    public function test_sync_can_import_a_page_saved_to_disk_and_dry_run_writes_nothing(): void
    {
        Http::preventStrayRequests();

        $this->artisan('srbijasport:sync', ['--file' => base_path(self::FIXTURE), '--dry-run' => true])
            ->expectsOutputToContain('Tabela: 11 timova · kolo: 2')
            ->assertSuccessful();
        $this->assertSame(0, StandingRow::count());

        $this->artisan('srbijasport:sync', ['--file' => base_path(self::FIXTURE)])->assertSuccessful();
        $this->assertCount(11, StandingRow::table()->get());
    }

    public function test_sync_fails_loudly_when_the_page_has_no_table(): void
    {
        $this->fakeSrbijasport('<html><body><p>Održavanje</p></body></html>');

        $this->artisan('srbijasport:sync')->assertFailed();
        $this->assertSame(0, StandingRow::count());
    }

    /** A minimal page in the srbijasport markup: two-team table, one of our games, a bye box. */
    private function snippet(int $round, string $homeScore, string $awayScore): string
    {
        return <<<HTML
        <html><body>
        <div class="ui-list">
          <div class="ui-list-item game-row" data-id="1">
            <div class="flex" data-res="">
              <div class="flex-col border-r"><div class="hidden sm:block">20.09.2026</div><div class="sm:hidden">20.09</div><div>16:00</div></div>
              <div><div class="team-host">Železničar</div><div class="team-guest">Supovac</div></div>
              <div class="game-res"><div class="res-host"><div class="text-base">{$homeScore}</div></div><div class="res-guest"><div class="text-base">{$awayScore}</div></div></div>
              <div class="game-row-icons"></div>
            </div>
          </div>
          <div class="border"><span>U ovom kolu slobodan je tim:</span><a href="#">Mezgraja</a></div>
        </div>
        <div id="league_tab" class="ssnet-table-wrapper" league="8780" round="{$round}" layout="standings">
        <table class="tab ssnet-table"><tbody>
          <tr tid="6"><td class="poz"><span class="pos-deleg">1</span></td><td class="col-PROG"></td>
            <td class="col-TIM"><div class="team-name">Železničar</div><div class="team-city">Niš</div></td>
            <td class="col-UTAKM">2</td><td class="col-POB">1</td><td class="col-NER">0</td><td class="col-POR">1</td>
            <td class="col-DG">4</td><td class="col-PG">4</td><td class="col-GR">0</td><td class="bod"><div class="pts-wrapper">3</div></td></tr>
          <tr tid="7"><td class="poz"><span class="pos-deleg">2</span></td><td class="col-PROG"></td>
            <td class="col-TIM"><div class="team-name">Supovac</div><div class="team-city">Supovac</div></td>
            <td class="col-UTAKM">1</td><td class="col-POB">0</td><td class="col-NER">0</td><td class="col-POR">1</td>
            <td class="col-DG">0</td><td class="col-PG">2</td><td class="col-GR">-2</td><td class="bod"><div class="pts-wrapper">0</div></td></tr>
        </tbody></table></div>
        </body></html>
        HTML;
    }
}
