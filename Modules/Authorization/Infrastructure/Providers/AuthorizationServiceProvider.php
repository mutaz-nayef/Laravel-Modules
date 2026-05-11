<?php

namespace Modules\Authorization\Infrastructure\Providers;

use Carbon\Laravel\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Modules\Authorization\Application\Actions\CheckPermissionAction;
use Modules\Authorization\Application\DTOs\Input\CheckPermissionInputDto;
use Modules\Authorization\Domain\Contracts\FieldGuardServiceInterface;
use Modules\Authorization\Domain\Contracts\PermissionRepositoryInterface;
use Modules\Authorization\Domain\Contracts\PolicyEngineInterface;
use Modules\Authorization\Domain\Contracts\RoleRepositoryInterface;
use Modules\Authorization\Domain\Services\FieldGuardService;
use Modules\Authorization\Domain\Services\PolicyEngine;
use Modules\Authorization\Domain\ValueObjects\ResourceAttributes;
use Modules\Authorization\Infrastructure\Repositories\PermissionRepository;
use Modules\Authorization\Infrastructure\Repositories\RoleRepository;
use Modules\Authorization\Presentation\Http\Middleware\AbacMiddleware;
use Modules\Authorization\Presentation\Http\Middleware\AdminMiddleware;
use Modules\Authorization\Presentation\Http\Middleware\FieldGuardMiddleware;
use Modules\Shared\Domain\ValueObjects\UserId;

class AuthorizationServiceProvider extends ServiceProvider

{
    public function boot(): void
    {

        Route::prefix('api')
            ->middleware('api')
            ->group(base_path('/Modules/Authorization/Infrastructure/Routes/api.php'));

        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->app['router']->aliasMiddleware('abac', AbacMiddleware::class);
        $this->app['router']->aliasMiddleware('field.guard', FieldGuardMiddleware::class);
        $this->app['router']->aliasMiddleware('admin', AdminMiddleware::class);
        // Gate integration — $user->can('posts:edit', $post) works everywhere in Laravel
        Gate::before(function ($user, string $ability, array $arguments = []) {
            $resource = isset($arguments[0]) && is_object($arguments[0])
                ? ResourceAttributes::from($arguments[0]->toArray())
                : ResourceAttributes::empty();

            $action = $this->app->make(CheckPermissionAction::class);

            $output = $action->execute(new CheckPermissionInputDto(
                userId: new UserId($user->id),
                permissionName: $ability,
                resource: $resource,
            ));

            // Return true/false explicitly (not null) so Gate uses our result
            return $output->allowed;
        });
    }

    public function register(): void
    {
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
        $this->app->bind(PolicyEngineInterface::class, PolicyEngine::class);
        $this->app->bind(FieldGuardServiceInterface::class, FieldGuardService::class);

    }
}
