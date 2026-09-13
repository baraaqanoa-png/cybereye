<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);

        \App\Models\User1::observe(\App\Observers\UserObserver::class);
        \Illuminate\Support\Facades\URL::forceScheme('https');
    }
}
