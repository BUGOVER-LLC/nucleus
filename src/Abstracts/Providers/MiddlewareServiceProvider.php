<?php

namespace Nucleus\Abstracts\Providers;

use Illuminate\Contracts\Container\BindingResolutionException;
use Nucleus\Loaders\MiddlewaresLoaderTrait;
use Nucleus\src\Abstracts\Providers\MainServiceProvider;

abstract class MiddlewareServiceProvider extends MainServiceProvider
{
    use MiddlewaresLoaderTrait;

    protected array $middlewares = [];

    protected array $middlewareGroups = [];

    protected array $middlewarePriority = [];

    protected array $routeMiddleware = [];

    /**
     * Perform post-registration booting of services.
     * @throws BindingResolutionException
     */
    public function boot(): void
    {
        $this->loadMiddlewares();
    }

    /**
     * Register anything in the container.
     */
    public function register(): void
    {
    }
}
