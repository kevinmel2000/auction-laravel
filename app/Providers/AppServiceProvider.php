<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Validator;
use Auth;
use Hash;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        switch (config('app.locale')) {
            case 'ru':
                setlocale(LC_TIME, 'ru_RU.utf8');
                break;
            case 'en':
                setlocale(LC_TIME, 'en_US.utf8');
                break;
        }

        Validator::extend('password_check', function($attribute, $value, $parameters, $validator) {
            return Auth::user()->password == Hash::make($value);
        });
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
