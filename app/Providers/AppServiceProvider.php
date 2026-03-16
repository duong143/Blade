<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $footerSettings = [];

        // ✅ Chỉ query khi bảng settings đã tồn tại (tránh crash migrate:fresh)
        if (Schema::hasTable('settings')) {
            $footerSettings = Setting::where('group', 'footer')
                ->pluck('value', 'key')
                ->toArray();
        }

        View::share('footerSettings', $footerSettings);

        Paginator::useBootstrapFive();
    }
}
