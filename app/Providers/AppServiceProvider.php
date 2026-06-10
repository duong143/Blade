<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $footerSettings = [];

        /*
         * Chỉ lấy dữ liệu footer khi hệ thống chạy trên trình duyệt.
         * Không truy vấn database khi chạy lệnh artisan như:
         * php artisan migrate, php artisan serve, php artisan config:clear...
         */
        if (!$this->app->runningInConsole()) {
            try {
                if (Schema::hasTable('settings')) {
                    $footerSettings = Setting::where('group', 'footer')
                        ->pluck('value', 'key')
                        ->toArray();
                }
            } catch (Throwable $e) {
                Log::warning('Không thể tải cấu hình footer: ' . $e->getMessage());

                $footerSettings = [];
            }
        }

        View::share('footerSettings', $footerSettings);

        Paginator::useBootstrapFive();
    }
}
