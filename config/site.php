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

];
