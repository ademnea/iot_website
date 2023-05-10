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
           //we need an if statement to first check if the table exists, before attempting to pick data.
   
            // Execute query to fetch data from my_table

    // if(Schema::hasTable('website_content')){
    //     $contents = website_content::all();
    //     View::share('contents', $contents);

    //      }

    }
}
