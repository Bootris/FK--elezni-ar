<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\FirstTeamController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\YouthController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect(app()->getLocale()));

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// News stays unprefixed for stable SEO URLs; chrome renders in the default locale.
Route::get('/vesti', [NewsController::class, 'index'])->name('news.index');
Route::get('/vesti/{slug}', [NewsController::class, 'show'])->name('news.show');

// Video — every post that carries a YouTube/Vimeo link.
Route::get('/video', [VideoController::class, 'index'])->name('video.index');

// Forms (honeypot + rate limit).
Route::post('/contact', [ContactController::class, 'submit'])
    ->middleware('throttle:5,1')
    ->name('contact.submit');
Route::post('/upis', [YouthController::class, 'apply'])
    ->middleware('throttle:5,1')
    ->name('youth.apply');

Route::group([
    'prefix' => '{locale}',
    'where' => ['locale' => 'en|sr'],
    'middleware' => 'setlocale',
], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/prvi-tim', [FirstTeamController::class, 'index'])->name('team.index');
    Route::get('/omladinci', [YouthController::class, 'index'])->name('youth.index');
    Route::get('/podrzi-klub', [SupportController::class, 'index'])->name('support.index');
    Route::get('/kontakt', [ContactController::class, 'index'])->name('contact.index');
});
