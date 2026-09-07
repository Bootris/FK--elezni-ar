<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Weekend results from FS Niš, pulled Sunday night (needs `* * * * * php artisan schedule:run` in cron).
Schedule::command('fsn:sync')->weeklyOn(0, '23:00');
