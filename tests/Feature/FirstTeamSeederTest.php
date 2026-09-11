<?php

namespace Tests\Feature;

use App\Models\Player;
use Database\Seeders\FirstTeamSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FirstTeamSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeds_players_with_photos_and_keeps_admin_edits(): void
    {
        Storage::fake('public');

        $this->seed(FirstTeamSeeder::class);

        $tasic = Player::where('slug', 'lazar-tasic')->firstOrFail();
        $this->assertSame('FW', $tasic->position);
        $this->assertSame('players/lazar-tasic.jpg', $tasic->photo);
        Storage::disk('public')->assertExists('players/lazar-tasic.jpg');
        $this->assertSame(5, Player::count());

        // The club moves him in the admin; a redeploy must not undo that.
        $tasic->update(['position' => 'MF', 'shirt_number' => 9]);
        $this->seed(FirstTeamSeeder::class);

        $tasic->refresh();
        $this->assertSame('MF', $tasic->position);
        $this->assertSame(9, $tasic->shirt_number);
        $this->assertSame(5, Player::count());
    }
}
