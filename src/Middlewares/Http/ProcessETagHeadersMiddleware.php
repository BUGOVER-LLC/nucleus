<?php

declare(strict_types=1);

namespace Nucleus\Middlewares\Http;

use Closure;
use Illuminate\Http\Request;
use Nucleus\Abstracts\Middleware\Middleware;
use Symfony\Component\HttpKernel\Exception\PreconditionFailedHttpException;

class ProcessETagHeadersMiddleware extends Middleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!config('nucleus.requests.use-etag', false)) {
            return $next($request);
        }

        if ($request->hasHeader('if-none-match')) {
            $method = $request->method();
            if (!('GET' === $method || 'HEAD' === $method)) {
                throw new PreconditionFailedHttpException(
                    'HTTP Header IF-None-Match is only allowed for GET and HEAD Requests.'
                );
            }
        }

        $response = $next($request);

        $content = $response->getContent();
        $etag = md5($content);
        $response->headers->set('Etag', $etag);

        if ($request->hasHeader('if-none-match') && $request->header('if-none-match') === $etag) {
            $response->setStatusCode(304);
        }

        return $response;
    }
}
