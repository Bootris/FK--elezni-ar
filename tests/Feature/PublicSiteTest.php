<?php

namespace Tests\Feature;

use App\Mail\ContactFormSubmitted;
use App\Mail\YouthApplicationConfirmation;
use App\Mail\YouthApplicationSubmitted;
use App\Models\ContactMessage;
use App\Models\FootballMatch;
use App\Models\Player;
use App\Models\Post;
use App\Models\Setting;
use App\Models\StandingRow;
use App\Models\YouthApplication;
use App\Models\YouthSelection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    use RefreshDatabase;

    private function publishedPost(array $attributes = []): Post
    {
        return Post::create(array_merge([
            'title' => 'Test vest',
            'slug' => 'test-vest',
            'excerpt' => 'Kratak opis.',
            'body' => '<p>Sadržaj vesti.</p>',
            'status' => 'published',
            'published_at' => now()->subDay(),
        ], $attributes));
    }

    public function test_root_redirects_to_default_locale(): void
    {
        $this->get('/')->assertRedirect('/sr');
    }

    public function test_home_renders_in_both_locales_with_club_ctas(): void
    {
        $this->get('/sr')->assertOk()->assertSee('Upiši se')->assertSee('Podrži klub');
        $this->get('/en')->assertOk()->assertSee('Join us')->assertSee('Support the club');
    }

    public function test_home_shows_next_match_last_result_and_table(): void
    {
        Setting::set('club_short_name', 'Železničar');
        FootballMatch::create([
            'competition' => 'Zona Istok', 'kickoff_at' => now()->addDays(3),
            'opponent' => 'Timok', 'is_home' => true, 'status' => 'scheduled',
        ]);
        FootballMatch::create([
            'competition' => 'Zona Istok', 'kickoff_at' => now()->subDays(4),
            'opponent' => 'Dubočica', 'is_home' => false, 'status' => 'finished',
            'our_score' => 2, 'their_score' => 1,
        ]);
        StandingRow::create(['position' => 1, 'team' => 'Železničar', 'points' => 9, 'is_club' => true]);

        $this->get('/sr')
            ->assertOk()
            ->assertSee('Timok')
            ->assertSee('Dubočica')
            ->assertSee('1:2', false);
    }

    public function test_first_team_page_lists_players_by_position(): void
    {
        Player::create(['name' => 'Nikola Golman', 'position' => 'GK', 'shirt_number' => 1]);
        Player::create(['name' => 'Skriveni Igrač', 'position' => 'FW', 'visible' => false]);

        $this->get('/sr/prvi-tim')
            ->assertOk()
            ->assertSee('Golman')
            ->assertDontSee('Skriveni');
    }

    public function test_youth_page_lists_selections(): void
    {
        YouthSelection::create(['name' => 'U-11', 'birth_years' => '2015/2016']);

        $this->get('/sr/omladinci')->assertOk()->assertSee('U-11')->assertSee('2015/2016');
        $this->get('/en/omladinci')->assertOk()->assertSee('Enrolment form');
    }

    public function test_support_and_contact_pages_render_settings(): void
    {
        Setting::set('bank_account', '160-0000000000-99');
        Setting::set('email', 'klub@example.test');

        $this->get('/sr/podrzi-klub')->assertOk()->assertSee('160-0000000000-99');
        $this->get('/sr/kontakt')->assertOk()->assertSee('klub@example.test');
    }

    public function test_news_index_shows_published_posts_only(): void
    {
        $this->publishedPost();
        Post::create([
            'title' => 'Nacrt vesti', 'slug' => 'nacrt-vesti', 'body' => '<p>Draft.</p>', 'status' => 'draft',
        ]);
        Post::create([
            'title' => 'Zakazana vest', 'slug' => 'zakazana-vest', 'body' => '<p>Scheduled.</p>',
            'status' => 'published', 'published_at' => now()->addWeek(),
        ]);

        $this->get('/vesti')
            ->assertOk()
            ->assertSee('Test vest')
            ->assertDontSee('Nacrt vesti')
            ->assertDontSee('Zakazana vest');
    }

    public function test_news_post_page_renders_with_video_embed(): void
    {
        $post = $this->publishedPost(['video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ']);

        $this->get("/vesti/{$post->slug}")
            ->assertOk()
            ->assertSee('Test vest')
            ->assertSee('youtube-nocookie.com/embed/dQw4w9WgXcQ', false);

        $this->get('/video')->assertOk()->assertSee('Test vest');
    }

    public function test_ticker_shows_pinned_posts_and_falls_back_to_newest(): void
    {
        $this->publishedPost(['title' => 'Najnovija vest', 'slug' => 'najnovija']);
        $older = $this->publishedPost(['title' => 'Pinovana vest', 'slug' => 'pinovana', 'published_at' => now()->subDays(3)]);

        // The contact page has no news cards, so any title there comes from the ticker.
        $this->get('/sr/kontakt')->assertSee('Najnovija vest')->assertSee('Pinovana vest');

        $older->update(['is_pinned' => true]);

        $this->get('/sr/kontakt')->assertSee('Pinovana vest')->assertDontSee('Najnovija vest');
    }

    public function test_draft_post_returns_404(): void
    {
        $this->publishedPost(['slug' => 'skriveni', 'status' => 'draft']);

        $this->get('/vesti/skriveni')->assertNotFound();
    }

    public function test_sitemap_lists_pages_and_published_posts(): void
    {
        $post = $this->publishedPost();

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml')
            ->assertSee("/vesti/{$post->slug}")
            ->assertSee('/sr/omladinci');
    }

    public function test_youth_application_is_stored_and_club_is_notified(): void
    {
        Mail::fake();
        Setting::set('youth_email', 'omladinci@example.test');
        $selection = YouthSelection::create(['name' => 'U-9']);

        $this->from('/sr/omladinci')->post('/upis', [
            'child_name' => 'Petar Petrović',
            'birth_year' => now()->year - 8,
            'parent_name' => 'Marko Petrović',
            'phone' => '+381 60 123 4567',
            'email' => 'marko@example.test',
            'youth_selection_id' => $selection->id,
            'note' => 'Trenirao godinu dana.',
            'consent' => '1',
        ])->assertRedirect('/sr/omladinci#upis')->assertSessionHas('enrol_success');

        $this->assertDatabaseHas('youth_applications', [
            'child_name' => 'Petar Petrović',
            'youth_selection_id' => $selection->id,
            'status' => 'new',
        ]);
        Mail::assertSent(YouthApplicationSubmitted::class, fn ($mail) => $mail->hasTo('omladinci@example.test'));
    }

    public function test_youth_application_requires_consent_and_valid_year(): void
    {
        $this->from('/sr/omladinci')->post('/upis', [
            'child_name' => 'Petar',
            'birth_year' => 1990,
            'parent_name' => 'Marko',
            'phone' => '060',
            'email' => 'marko@example.test',
        ])->assertRedirect('/sr/omladinci')->assertSessionHasErrors(['birth_year', 'consent']);

        $this->assertSame(0, YouthApplication::count());
    }

    public function test_youth_application_email_is_optional(): void
    {
        Mail::fake();
        Setting::set('youth_email', 'omladinci@example.test');

        $this->from('/sr/omladinci')->post('/upis', [
            'child_name' => 'Petar Petrović',
            'birth_year' => now()->year - 8,
            'parent_name' => 'Marko Petrović',
            'phone' => '+381 60 123 4567',
            'email' => '',
            'consent' => '1',
        ])->assertRedirect('/sr/omladinci#upis')->assertSessionHas('enrol_success');

        $this->assertDatabaseHas('youth_applications', ['child_name' => 'Petar Petrović', 'email' => null]);
        Mail::assertSent(YouthApplicationSubmitted::class);
        Mail::assertNotSent(YouthApplicationConfirmation::class);
    }

    public function test_youth_application_honeypot_blocks_bots_silently(): void
    {
        $this->post('/upis', [
            'child_name' => 'Bot', 'birth_year' => now()->year - 8, 'parent_name' => 'Bot',
            'phone' => '0', 'email' => 'bot@example.test', 'website' => 'http://spam.example',
        ])->assertRedirect();

        $this->assertSame(0, YouthApplication::count());
    }

    public function test_contact_form_stores_message_and_sends_mail(): void
    {
        Mail::fake();
        Setting::set('email', 'klub@example.test');

        $this->from('/sr/kontakt')->post('/contact', [
            'name' => 'Petar Petrović',
            'email' => 'petar@example.test',
            'message' => 'Pitanje o saradnji.',
        ])->assertRedirect('/sr/kontakt')->assertSessionHas('contact_success');

        $this->assertDatabaseHas('contact_messages', ['email' => 'petar@example.test']);
        Mail::assertSent(ContactFormSubmitted::class);
    }

    public function test_contact_honeypot_blocks_bots_silently(): void
    {
        $this->post('/contact', [
            'name' => 'Bot', 'email' => 'bot@example.test', 'message' => 'spam', 'website' => 'http://spam.example',
        ])->assertRedirect();

        $this->assertSame(0, ContactMessage::count());
    }

    public function test_public_api_exposes_club_data(): void
    {
        Player::create(['name' => 'Nikola Golman', 'position' => 'GK']);
        YouthSelection::create(['name' => 'U-9']);
        StandingRow::create(['position' => 1, 'team' => 'Železničar', 'points' => 9, 'is_club' => true]);

        $this->getJson('/api/v1/squad')->assertOk()->assertJsonPath('players.0.name', 'Nikola Golman');
        $this->getJson('/api/v1/youth')->assertOk()->assertJsonPath('selections.0.name', 'U-9');
        $this->getJson('/api/v1/standings')->assertOk()->assertJsonPath('0.points', 9);
        $this->getJson('/api/v1/settings')->assertOk()->assertJsonStructure(['support' => ['bank_account']]);
    }
}
