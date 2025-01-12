<?php

declare(strict_types=1);

namespace Nucleus\Abstracts\Provider;

use Illuminate\Contracts\Container\BindingResolutionException;
use Nucleus\Loaders\MiddlewaresLoaderTrait;
use Illuminate\Support\ServiceProvider as LaravelAppServiceProvider;

abstract class MiddlewareServiceProvider extends LaravelAppServiceProvider
{
    use MiddlewaresLoaderTrait;

    protected array $middlewares = [];

    protected array $middlewareGroups = [];

    protected array $middlewarePriority = [];

    protected array $routeMiddleware = [];

    /**
     * Perform post-registration booting of services.
     *
     * @throws BindingResolutionException
     */
    public function boot(): void
    {
        $this->loadMiddlewares();
    }
}
