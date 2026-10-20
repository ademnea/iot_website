<?php

namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use App\Models\website_content;
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
    public function boot(): void
    {
        // The Fortify/Jetstream auth pages (login, forgot/reset password, confirm password) show
        // the lab logo from website_content, but Fortify renders them without $contents.
        // A composer only queries when one of these views renders, so it is safe before migrations
        // (unlike View::share in boot, which was tried here before).
        View::composer(['auth.*', 'components.authentication-card'], function ($view) {
            if (! array_key_exists('contents', $view->getData())) {
                $view->with('contents', website_content::all());
            }
            // the customised login view shows a $status line, as the branded login page does.
            if (! array_key_exists('status', $view->getData())) {
                $view->with('status', session('status', ''));
            }
        });
    }
}
