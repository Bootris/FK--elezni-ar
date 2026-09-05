<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ClubController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\SettingsController;
use Illuminate\Support\Facades\Route;

/*
| Public read-only content API for headless frontends (mobile app, Astro…).
| The Blade site in this repo does not use it. Changing a response shape
| means a new /v2 group — /v1 stays.
*/
Route::prefix('v1')->group(function () {
    Route::get('settings', [SettingsController::class, 'show']);

    Route::get('posts', [PostController::class, 'index']);
    Route::get('posts/{slug}', [PostController::class, 'show']);
    Route::get('categories', [CategoryController::class, 'index']);

    Route::get('squad', [ClubController::class, 'squad']);
    Route::get('matches', [ClubController::class, 'matches']);
    Route::get('standings', [ClubController::class, 'standings']);
    Route::get('youth', [ClubController::class, 'youth']);

    Route::post('contact', [ContactController::class, 'store'])
        ->middleware('throttle:5,1');
    Route::post('enrol', [ClubController::class, 'enrol'])
        ->middleware('throttle:5,1');
});
