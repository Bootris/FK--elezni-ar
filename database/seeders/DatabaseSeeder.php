<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Bootstrap a fresh club site: admin user, news categories and the
     * identity/contact settings the frontend needs. Idempotent — re-seeding
     * never overwrites values edited in the admin.
     */
    public function run(): void
    {
        $admin = config('site.admin');

        User::updateOrCreate(
            ['email' => $admin['email']],
            [
                'name' => $admin['name'],
                'password' => $admin['password'],   // hashed via User cast
                'role' => User::ROLE_ADMIN,
            ],
        );

        foreach (config('site.categories', []) as $name) {
            Category::firstOrCreate(['name' => $name]);
        }

        $defaults = [
            'site_name' => config('site.name'),
            'club_short_name' => config('site.short_name'),
            'tagline' => config('site.tagline'),
            'founded_year' => '1928',
            'city' => 'Niš',
            'stadium' => 'Stadion Železničar, Niš',
            'season' => '2026/27',
            'league_name' => config('site.fsn.league_name'),

            'hero_kicker' => 'Fudbalski klub · Niš · od 1928.',
            'hero_title' => 'Železnica ne staje.',
            'hero_subtitle' => 'Klub koji odrasta sa svojim gradom. Omladinska škola za sve uzraste, prvi tim koji se bori za svaki bod i navijači koji ne odustaju.',

            'youth_intro' => 'Omladinska škola FK Železničar okuplja decu iz Niša i okoline od predškolskog uzrasta do omladinaca. Licencirani treneri, jasan plan razvoja i put do prvog tima.',
            'youth_age_range' => 'od 5 do 19 godina',
            'youth_training_info' => 'Treninzi se održavaju tri puta nedeljno na terenima kluba. Prvi trening je besplatan i bez obaveza: dođite, upoznajte trenere i probajte.',
            'youth_phone' => '+381 60 000 0000',
            'youth_email' => 'omladinci@fkzeleznicar.rs',

            'support_intro' => 'Železničar je klub koji živi od svojih ljudi. Svaka uplata ide direktno u rad omladinske škole, opremu i takmičenja. Hvala što ste uz nas.',
            'account_holder' => 'FK Železničar Niš',
            'bank_name' => '',
            'bank_account' => '000-0000000000000-00',
            'payment_purpose' => 'Donacija klubu',
            'payment_code' => '289',
            'payment_model' => '',
            'payment_reference' => '',
            'support_note' => 'Za donacije iz inostranstva i sponzorske ugovore pišite nam na imejl kluba.',

            'address' => 'Niš, Srbija',
            'email' => 'klub@fkzeleznicar.rs',
            'phone' => '+381 18 000 000',
            'working_hours' => 'Pon–Pet · 10–18h',

            'seo_description' => 'FK Železničar Niš, zvanični sajt kluba. Vesti, prvi tim, omladinska škola, upis novih igrača i podrška klubu.',
        ];

        foreach ($defaults as $key => $value) {
            if ($value !== null && $value !== '' && Setting::query()->where('key', $key)->doesntExist()) {
                Setting::set($key, $value);
            }
        }

        if (config('site.seed_demo')) {
            $this->call(ZeleznicarDemoSeeder::class);
        }

        // Real players with official photos — after the demo so they keep their data.
        $this->call(FirstTeamSeeder::class);
    }
}
