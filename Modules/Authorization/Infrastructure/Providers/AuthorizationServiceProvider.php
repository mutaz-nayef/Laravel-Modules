<?php

namespace Modules\Authorization\Infrastructure\Providers;

use Carbon\Laravel\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Modules\Authorization\Domain\Contracts\PermissionRepositoryInterface;
use Modules\Authorization\Domain\Contracts\RoleRepositoryInterface;
use Modules\Authorization\Infrastructure\Repositories\PermissionRepository;
use Modules\Authorization\Infrastructure\Repositories\RoleRepository;

class AuthorizationServiceProvider extends ServiceProvider

{
    public function boot(): void
    {
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);

        Route::prefix('api')
            ->middleware('api')
            ->group(base_path('/Modules/Authorization/Infrastructure/Routes/api.php'));

        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

    }

    public function register(): void
    {
    }
}
