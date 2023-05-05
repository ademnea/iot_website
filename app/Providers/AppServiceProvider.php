<?php

namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use App\Models\website_content;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
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

    if(Schema::hasTable('website_content')){
        $contents = website_content::all();
        View::share('contents', $contents);

         }
    }
}
