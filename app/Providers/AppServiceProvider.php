<?php

namespace App\Providers;

use App\Models\user_reservation_requests;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

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
    public function boot()
    {
        view()->composer('*', function ($view) {
            $user = Auth::user();
            $requests_count = 0;

            if ($user && $user->user_type != 3) {
                $requests_count = user_reservation_requests::where('professor', $user->user_id)
                    ->where('status', 2)
                    ->whereNull('noted_by')
                    ->count();
            }

            // Share $requests_count globally to all views
            View::share('requests_count', $requests_count);
        });
    }
}
