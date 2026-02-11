<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;


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
        $footerSettings = Setting::where('group', 'footer')
            ->pluck('value', 'key')
            ->toArray();

        View::share('footerSettings', $footerSettings);

        Paginator::useBootstrapFive();
    }
}
