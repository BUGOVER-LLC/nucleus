<?php

declare(strict_types=1);

namespace Nucleus\Middlewares\Http;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Nucleus\Abstracts\Middlewares\Middleware;

class ProfilerMiddleware extends Middleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (!config('debugbar.enabled')) {
            return $response;
        }

        if ($response instanceof JsonResponse && app()->bound('debugbar')) {
            $profilerData = ['_profiler' => app('debugbar')->getData()];

            $response->setData($response->getData(true) + $profilerData);
        }

        return $response;
    }
}
