<?php

declare(strict_types=1);

namespace Nucleus\Loaders;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Http\Kernel;

trait MiddlewaresLoaderTrait
{
    /**
     * @void
     * @throws BindingResolutionException
     */
    public function loadMiddlewares(): void
    {
        if (!empty($this->middlewares)) {
            $this->registerMiddleware($this->middlewares);
        }
        if (!empty($this->middlewareGroups)) {
            $this->registerMiddlewareGroups($this->middlewareGroups);
        }
        if (!empty($this->middlewarePriority)) {
            $this->registerMiddlewarePriority($this->middlewarePriority);
        }
        if (!empty($this->routeMiddleware)) {
            $this->registerRouteMiddleware($this->routeMiddleware);
        }
    }

    /**
     * Registering Route Group's
     *
     * @param array $middlewares
     * @throws BindingResolutionException
     */
    private function registerMiddleware(array $middlewares = []): void
    {
        $httpKernel = $this->app->make(Kernel::class);

        foreach ($middlewares as $middleware) {
            $httpKernel->prependMiddleware($middleware);
        }
    }

    /**
     * Registering Route Group's
     *
     * @param array $middlewareGroups
     */
    private function registerMiddlewareGroups(array $middlewareGroups = []): void
    {
        foreach ($middlewareGroups as $key => $middleware) {
            if (\is_array($middleware)) {
                foreach ($middleware as $item) {
                    $this->app['router']->pushMiddlewareToGroup($key, $item);
                }
            } else {
                $this->app['router']->pushMiddlewareToGroup($key, $middleware);
            }
        }
    }

    /**
     * Registering Route Middleware's priority
     *
     * @param array $middlewarePriority
     */
    private function registerMiddlewarePriority(array $middlewarePriority = []): void
    {
        foreach ($middlewarePriority as $key => $middleware) {
            if (!\in_array($middleware, $this->app['router']->middlewarePriority, true)) {
                $this->app['router']->middlewarePriority[] = $middleware;
            }
        }
    }

    /**
     * Registering Route Middleware's
     *
     * @param array $routeMiddleware
     */
    private function registerRouteMiddleware(array $routeMiddleware = []): void
    {
        foreach ($routeMiddleware as $key => $value) {
            $this->app['router']->aliasMiddleware($key, $value);
        }
    }
}
