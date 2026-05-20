<?php

namespace Modules\Shared\Infrastructure\Providers;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;
use Modules\Shared\Abstractions\EventDispatcherInterface;
use Modules\Shared\Infrastructure\Services\LaravelEventDispatcher;
use Modules\Shared\Presentation\Http\Middlewares\LocalizationMiddleware;

class SharedServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(EventDispatcherInterface::class, LaravelEventDispatcher::class);
    }

    public function boot(Kernel $kernel): void
    {
        $kernel->pushMiddleware(LocalizationMiddleware::class);
    }
}
