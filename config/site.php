<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Admin panel path
    |--------------------------------------------------------------------------
    | URL segment where the Filament admin is mounted. Give each deployment a
    | random slug in production (e.g. admin-x7k2p9) via ADMIN_PATH.
    */

    'admin_path' => env('ADMIN_PATH', 'admin'),

    /*
    |--------------------------------------------------------------------------
    | Club identity defaults
    |--------------------------------------------------------------------------
    | Used when seeding a fresh site. After seeding, the live values are edited
    | in Admin → Podešavanja sajta (the database always wins over these).
    */

    'name' => env('SITE_NAME', 'FK Železničar Niš'),
    'short_name' => env('SITE_SHORT_NAME', 'Železničar'),
    'tagline' => env('SITE_TAGLINE', 'Fudbalski klub iz Niša · od 1928.'),
    'locale' => env('APP_LOCALE', 'sr'),

    /*
    | Seeded admin account (change the password immediately after first login).
    */
    'admin' => [
        'name' => env('SEED_ADMIN_NAME', 'Administrator'),
        'email' => env('SEED_ADMIN_EMAIL', 'admin@example.com'),
        'password' => env('SEED_ADMIN_PASSWORD', 'password'),
    ],

    /*
    | Seed demo content (squad, staff, selections, fixtures, table, news) so a
    | fresh install looks finished. Set SEED_DEMO=false for a blank site.
    */
    'seed_demo' => env('SEED_DEMO', true),

    /*
    | News categories created on first seed.
    */
    'categories' => ['Prvi tim', 'Omladinci', 'Klub', 'Utakmice'],

    /*
    | League data source — Fudbalski savez Niša. `php artisan fsn:sync` (scheduled
    | daily) pulls the standings and the first team's fixtures/results from this
    | page; rows can still be edited by hand in the admin in between.
    */
    'fsn' => [
        'url' => env('FSN_LEAGUE_URL', 'https://fsn.org.rs/druga-niska-liga'),
        'league_name' => env('FSN_LEAGUE_NAME', 'Druga niška liga'),
        'competition' => 'first',
    ],

];
