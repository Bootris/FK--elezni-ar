<?php

namespace Database\Seeders;

use App\Models\Player;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Real first-team players with their official photos. The photos live in the
 * repo (database/seeders/assets/players) because storage/ is neither versioned
 * nor rsynced to production; on run they are copied into the public disk, where
 * the admin's own uploads go.
 *
 * Idempotent and admin-friendly: a player that already exists keeps everything
 * the club edited in the admin — only a missing photo is filled in.
 * Runs on every deploy (bin/deploy-production.sh) and with `php artisan db:seed`.
 */
class FirstTeamSeeder extends Seeder
{
    /** name, position */
    private const PLAYERS = [
        ['Nenad Milosavljević', 'DF'],
        ['Martin Ilić', 'DF'],
        ['Miloš Milojević', 'MF'],
        ['Nemanja Damjanović', 'MF'],
        ['Lazar Tasić', 'FW'],
    ];

    public function run(): void
    {
        $disk = Storage::disk('public');

        foreach (self::PLAYERS as [$name, $position]) {
            $slug = Str::slug($name);
            $source = __DIR__ . "/assets/players/{$slug}.jpg";
            $target = "players/{$slug}.jpg";

            if (is_file($source) && ! $disk->exists($target)) {
                $disk->put($target, file_get_contents($source));
            }

            $player = Player::firstOrNew(['slug' => $slug]);

            if (! $player->exists) {
                $player->fill([
                    'name' => $name,
                    'position' => $position,
                    'nationality' => 'Srbija',
                    'visible' => true,
                ]);
            }

            if (! $player->photo && $disk->exists($target)) {
                $player->photo = $target;
            }

            $player->save();
        }
    }
}
