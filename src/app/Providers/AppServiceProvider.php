<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        Validator::extend('image_extension', function ($attribute, $value, $parameters, $validator) {
            $extension = strtolower(pathinfo($value, PATHINFO_EXTENSION));
            return in_array($extension, ['jpg', 'jpeg', 'png']);
        }, '画像はjpegまたはpng形式である必要があります。');
    }
}
