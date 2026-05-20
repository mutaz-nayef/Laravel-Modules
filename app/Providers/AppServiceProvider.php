<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Authentication\Infrastructure\Providers\AuthServiceProvider;
use Modules\Authorization\Infrastructure\Providers\AuthorizationServiceProvider;
use Modules\Notifications\Infrastructure\Providers\NotificationServiceProvider;
use Modules\Shared\Infrastructure\Providers\SharedServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->register(AuthServiceProvider::class);
        $this->app->register(AuthorizationServiceProvider::class);
//        $this->app->register(NotificationServiceProvider::class);
        $this->app->register(SharedServiceProvider::class);
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
}
