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
        // Every view that shows the lab's name, logo or contacts gets $contents (the website_content
        // row) here, instead of each controller loading it. A composer only queries when one of
        // these views renders, so it is safe before migrations (unlike View::share in boot, which
        // was tried here before).
        View::composer([
            // public pages (PublicPageController) and the shared layout
            'index', 'about_us', 'projects', 'publications', 'news', 'partners',
            'gallery', 'team_gallery', 'project_gallery', 'prototypes_gallery', 'events_gallery',
            'layouts.public',
            // admin pages that show the home content
            'homecontent',
            // login, register-an-admin and the Fortify/Jetstream auth pages
            'oldlogin', 'register', 'auth.*', 'components.authentication-card',
        ], function ($view) {
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
