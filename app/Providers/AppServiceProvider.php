<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Image;
use App\Observers\ImageObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Image::observe(ImageObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
