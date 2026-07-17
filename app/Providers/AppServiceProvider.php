<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;

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
        RateLimiter::for('orders', function (Request $request) {
            return Limit::perMinute(config('settings.rate_limits.orders', 3))->by($request->ip());
        });

        // Clear Dashboard Cache on changes
        $clearCache = function () {
            \App\Services\DashboardService::clearCache();
        };

        \App\Models\Order::saved($clearCache);
        \App\Models\Order::deleted($clearCache);
        \App\Models\Project::saved($clearCache);
        \App\Models\Project::deleted($clearCache);
        \App\Models\Service::saved($clearCache);
        \App\Models\Service::deleted($clearCache);
    }
}
