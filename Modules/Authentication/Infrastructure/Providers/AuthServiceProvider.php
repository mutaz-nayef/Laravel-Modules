<?php

namespace Modules\Authentication\Infrastructure\Providers;

use Carbon\Laravel\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Modules\Authentication\Domain\Contracts\EmailVerificationInterface;
use Modules\Authentication\Domain\Contracts\PasswordHasherInterface;
use Modules\Authentication\Domain\Contracts\PasswordResetInterface;
use Modules\Authentication\Domain\Contracts\PasswordVerifierInterface;
use Modules\Authentication\Domain\Contracts\TokenIssuerInterface;
use Modules\Authentication\Domain\Contracts\UserRepositoryInterface;
use Modules\Authentication\Infrastructure\Models\UserModel;
use Modules\Authentication\Infrastructure\Repositories\EloquentUserRepository;
use Modules\Authentication\Infrastructure\Services\Security\LaravelEmailVerification;
use Modules\Authentication\Infrastructure\Services\Security\LaravelPasswordReset;
use Modules\Authentication\Infrastructure\Services\Security\LaravelSanctumToken;
use Modules\Authentication\Infrastructure\Services\Security\PasswordHasherResolver;
use Modules\Authentication\Infrastructure\Services\Security\PasswordVerifier;

class AuthServiceProvider extends ServiceProvider

{
    public function boot(): void
    {
        $this->app->register(EventServiceProvide::class);

        Route::prefix('api')
            ->middleware('api')
            ->group(base_path('/Modules/Authentication/Infrastructure/Routes/api.php'));

        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');

        $this->loadFactoriesFrom(__DIR__.'/../Database/migrations/Database/factories');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../Config/auth-module.php', 'auth-module'
        );

        config([
            'auth.providers.users.model' =>
                UserModel::class,
        ]);

        $this->app->bind(TokenIssuerInterface::class, LaravelSanctumToken::class);
        $this->app->bind(PasswordResetInterface::class, LaravelPasswordReset::class);
        $this->app->bind(EmailVerificationInterface::class, LaravelEmailVerification::class);
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);


        $this->app->bind(PasswordVerifierInterface::class, PasswordVerifier::class);
        $this->app->bind(PasswordHasherInterface::class, function ($app) {
            $resolver = $app->make(PasswordHasherResolver::class);
            return $this->app->make($resolver->resolve());
        });
    }
}
