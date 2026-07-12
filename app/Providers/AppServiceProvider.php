<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

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
        // Share notification data with all views for authenticated users
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $userNotifications = Notification::where('user_id', Auth::id())
                    ->latest()
                    ->take(15)
                    ->get();
                $unreadNotifCount = Notification::where('user_id', Auth::id())
                    ->where('is_read', false)
                    ->count();
                $view->with('userNotifications', $userNotifications);
                $view->with('unreadNotifCount', $unreadNotifCount);
            }
        });
    }
}
