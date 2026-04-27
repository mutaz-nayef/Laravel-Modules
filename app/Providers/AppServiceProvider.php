<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Authentication\Infrastructure\Providers\AuthServiceProvider;

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
        $this->app->register(AuthServiceProvider::class);
    }
}
