<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Both need `* * * * * php artisan schedule:run` in the server's cron.
// Full fixture list + table from FS Niš, once a week after the weekend round.
Schedule::command('fsn:sync')->weeklyOn(0, '21:00');
// Live results: table + current round from srbijasport.net, every 15 minutes through match hours.
Schedule::command('srbijasport:sync')->everyFifteenMinutes()->between('10:00', '22:30');
