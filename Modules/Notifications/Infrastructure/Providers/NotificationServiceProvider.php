<?php

namespace Modules\Notifications\Infrastructure\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\Notifications\Domain\Contracts\NotificationChannelRepositoryInterface;
use Modules\Notifications\Domain\Contracts\NotificationPreferencesRepositoryInterface;
use Modules\Notifications\Domain\Contracts\NotificationRepositoryInterface;
use Modules\Notifications\Domain\Contracts\NotificationServiceInterface;
use Modules\Notifications\Domain\Contracts\NotificationTypeRepositoryInterface;
use Modules\Notifications\Infrastructure\Repositories\EloquentNotificationChannelRepository;
use Modules\Notifications\Infrastructure\Repositories\EloquentNotificationPreferencesRepository;
use Modules\Notifications\Infrastructure\Repositories\EloquentNotificationRepository;
use Modules\Notifications\Infrastructure\Repositories\EloquentNotificationTypeRepository;
use Modules\Notifications\Infrastructure\Services\LaravelNotificationService;

class NotificationServiceProvider extends ServiceProvider
{

    public function boot(): void
    {

        Route::prefix('api')
            ->middleware('api')
            ->group(base_path('/Modules/Notifications/Infrastructure/Routes/api.php'));

        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');


    }

    public function register(): void
    {
        $this->app->bind(NotificationRepositoryInterface::class, EloquentNotificationRepository::class);
        $this->app->bind(NotificationTypeRepositoryInterface::class, EloquentNotificationTypeRepository::class);
        $this->app->bind(NotificationChannelRepositoryInterface::class, EloquentNotificationChannelRepository::class);

        $this->app->bind(NotificationPreferencesRepositoryInterface::class,
            EloquentNotificationPreferencesRepository::class);
        $this->app->bind(NotificationServiceInterface::class,
            LaravelNotificationService::class);
    }


}
