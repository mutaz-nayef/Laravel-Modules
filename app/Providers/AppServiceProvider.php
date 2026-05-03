<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Authentication\Infrastructure\Providers\AuthServiceProvider;
use Modules\Authorization\Infrastructure\Providers\AuthorizationServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->register(AuthServiceProvider::class);
        $this->app->register(AuthorizationServiceProvider::class);
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
}
