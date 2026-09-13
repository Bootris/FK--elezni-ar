<?php

namespace Tests\Feature;

use App\Filament\Pages\ManageLiveStream;
use App\Filament\Pages\ManageSettings;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\FootballMatch;
use App\Models\Photo;
use App\Models\Setting;
use App\Models\Player;
use App\Models\Post;
use App\Models\StaffMember;
use App\Models\StandingRow;
use App\Models\User;
use App\Models\YouthApplication;
use App\Models\YouthSelection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => User::ROLE_ADMIN]);
    }

    private function editor(): User
    {
        return User::factory()->create(['role' => User::ROLE_EDITOR]);
    }

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_admin_can_open_dashboard_and_all_resources(): void
    {
        Post::create([
            'title' => 'Vest', 'slug' => 'vest', 'body' => '<p>x</p>',
            'status' => 'published', 'published_at' => now(),
        ]);
        Category::create(['name' => 'Prvi tim']);
        $selection = YouthSelection::create(['name' => 'U-9']);
        Player::create(['name' => 'Igrač Test', 'position' => 'MF']);
        StaffMember::create(['name' => 'Trener Test', 'role' => 'Trener', 'department' => 'youth', 'youth_selection_id' => $selection->id]);
        FootballMatch::create(['competition' => 'Liga', 'kickoff_at' => now()->addDay(), 'opponent' => 'Gost']);
        StandingRow::create(['position' => 1, 'team' => 'Železničar', 'is_club' => true]);
        Photo::create(['image' => 'gallery/x.jpg', 'album' => 'youth']);
        ContactMessage::create(['name' => 'Roditelj', 'email' => 'r@example.test', 'message' => 'Upit']);
        YouthApplication::create([
            'child_name' => 'Dete', 'birth_year' => 2016, 'parent_name' => 'Roditelj',
            'phone' => '060', 'email' => 'r@example.test',
        ]);

        $admin = $this->admin();

        foreach ([
            '/admin',
            '/admin/posts',
            '/admin/posts/create',
            '/admin/categories',
            '/admin/photos',
            '/admin/players',
            '/admin/players/create',
            '/admin/staff-members',
            '/admin/staff-members/create',
            '/admin/matches',
            '/admin/matches/create',
            '/admin/standing-rows',
            '/admin/youth-selections',
            '/admin/youth-selections/create',
            '/admin/youth-applications',
            '/admin/contact-messages',
            '/admin/users',
            '/admin/manage-settings',
            '/admin/manage-live-stream',
        ] as $url) {
            $this->actingAs($admin)->get($url)->assertOk();
        }
    }

    public function test_viewing_a_youth_application_marks_it_read(): void
    {
        $application = YouthApplication::create([
            'child_name' => 'Dete', 'birth_year' => 2016, 'parent_name' => 'Roditelj',
            'phone' => '060', 'email' => 'r@example.test',
        ]);

        $this->actingAs($this->admin())
            ->get("/admin/youth-applications/{$application->id}")
            ->assertOk()
            ->assertSee('Dete');

        $this->assertNotNull($application->fresh()->read_at);
    }

    public function test_viewing_a_contact_message_marks_it_read(): void
    {
        $message = ContactMessage::create(['name' => 'Roditelj', 'email' => 'r@example.test', 'message' => 'Upit']);

        $this->actingAs($this->admin())
            ->get("/admin/contact-messages/{$message->id}")
            ->assertOk();

        $this->assertNotNull($message->fresh()->read_at);
    }

    public function test_live_stream_page_saves_and_rejects_unembeddable_links(): void
    {
        $this->actingAs($this->admin());

        Livewire::test(ManageLiveStream::class)
            ->fillForm([
                'live_enabled' => true,
                'live_url' => 'https://www.youtube.com/@FKZeleznicarNis/live',
            ])
            ->call('save')
            ->assertHasFormErrors(['live_url']);

        Livewire::test(ManageLiveStream::class)
            ->fillForm([
                'live_enabled' => true,
                'live_url' => 'https://www.youtube.com/live/jNQXAC9IVRw',
                'live_title' => 'Železničar – Sinđelić',
                'live_note' => 'Stadion Čair',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame('https://www.youtube.com/live/jNQXAC9IVRw', Setting::get('live_url'));
        $this->assertSame('Železničar – Sinđelić', Setting::get('live_title'));
        $this->assertTrue(filter_var(Setting::get('live_enabled'), FILTER_VALIDATE_BOOLEAN));
    }

    public function test_editor_can_start_and_stop_a_live_broadcast(): void
    {
        $this->actingAs($this->editor())->get('/admin/manage-live-stream')->assertOk();

        Livewire::actingAs($this->editor())
            ->test(ManageLiveStream::class)
            ->fillForm([
                'live_enabled' => true,
                'live_url' => 'https://www.youtube.com/live/jNQXAC9IVRw',
                'live_title' => 'Prenos utakmice',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertTrue(filter_var(Setting::get('live_enabled'), FILTER_VALIDATE_BOOLEAN));

        // The one-click stop button takes the bar down without touching the link.
        Livewire::actingAs($this->editor())
            ->test(ManageLiveStream::class)
            ->callAction('stop');

        $this->assertFalse(filter_var(Setting::get('live_enabled'), FILTER_VALIDATE_BOOLEAN));
        $this->assertSame('https://www.youtube.com/live/jNQXAC9IVRw', Setting::get('live_url'));
    }

    public function test_saving_site_settings_leaves_the_live_broadcast_alone(): void
    {
        Setting::set('live_enabled', true);
        Setting::set('live_url', 'https://www.youtube.com/live/jNQXAC9IVRw');

        Livewire::actingAs($this->admin())
            ->test(ManageSettings::class)
            ->fillForm([
                'site_name' => 'FK Železničar Niš',
                'club_short_name' => 'Železničar',
                'bank_account' => '160-0000000000000-00',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertTrue(filter_var(Setting::get('live_enabled'), FILTER_VALIDATE_BOOLEAN));
        $this->assertSame('https://www.youtube.com/live/jNQXAC9IVRw', Setting::get('live_url'));
    }

    public function test_editor_can_manage_content_but_not_users_or_settings(): void
    {
        $editor = $this->editor();

        $this->actingAs($editor)->get('/admin/posts')->assertOk();
        $this->actingAs($editor)->get('/admin/players')->assertOk();
        $this->actingAs($editor)->get('/admin/youth-applications')->assertOk();
        $this->actingAs($editor)->get('/admin/manage-live-stream')->assertOk();
        $this->actingAs($editor)->get('/admin/users')->assertForbidden();
        $this->actingAs($editor)->get('/admin/manage-settings')->assertForbidden();
    }

    public function test_users_without_panel_role_cannot_access_admin(): void
    {
        $user = User::factory()->create(['role' => 'none']);

        $this->actingAs($user)->get('/admin')->assertForbidden();
    }
}
