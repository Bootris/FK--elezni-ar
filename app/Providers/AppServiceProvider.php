<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Every view (pages, components, mails) receives $site — the key/value
        // settings managed in the admin. Resolved at render time so a change
        // saved in the admin is visible immediately; guarded so console
        // commands work before migrations run.
        View::composer('*', function ($view) {
            try {
                $view->with('site', Setting::allCached());
            } catch (\Throwable) {
                $view->with('site', []);
            }
        });
    }
}
