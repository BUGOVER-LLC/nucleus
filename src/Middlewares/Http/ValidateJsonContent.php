<?php

declare(strict_types=1);

namespace Nucleus\Middlewares\Http;

use Closure;
use Illuminate\Http\Request;
use Nucleus\Abstracts\Middlewares\Middleware;
use Nucleus\Exceptions\MissingJSONHeaderException;

class ValidateJsonContent extends Middleware
{
    /**
     * @throws MissingJSONHeaderException
     */
    public function handle(Request $request, Closure $next)
    {
        $accept_header = $request->header('accept');
        $content_type = 'application/json';

        if (!str_contains($accept_header, $content_type) && config('nucleus.requests.force-accept-header')) {
            throw new MissingJSONHeaderException();
        }

        $response = $next($request);

        $response->headers->set('Content-Type', $content_type);

        if (!str_contains($accept_header, $content_type)) {
            $warn_code = '199';
            $warn_message = 'Missing request header [ accept = ' . $content_type . ' ] when calling a JSON API.';
            $response->headers->set('Warning', $warn_code . ' ' . $warn_message);
        }

        return $response;
    }
}
