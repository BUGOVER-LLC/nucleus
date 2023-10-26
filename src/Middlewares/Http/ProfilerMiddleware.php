<?php

declare(strict_types=1);

namespace Nucleus\Middlewares\Http;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Nucleus\Abstracts\Middlewares\Middleware;

class ProfilerMiddleware extends Middleware
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return JsonResponse|mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $response = $next($request);

        if (!config('debugbar.enabled')) {
            return $response;
        }

        if ($response instanceof JsonResponse && app()->bound('debugbar')) {
            $profiler_data = ['_profiler' => app('debugbar')->getData()];

            $response->setData($response->getData(true) + $profiler_data);
        }

        return $response;
    }
}
