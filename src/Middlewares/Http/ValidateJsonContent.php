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

        // check if the accept header is set to application/json
        // if forcing users to have the accept header is enabled, then throw an exception
        if (!str_contains($accept_header, $content_type) && config('nucleus.requests.force-accept-header')) {
            throw new MissingJSONHeaderException();
        }

        // the request has to be processed, so get the response after the request is done
        $response = $next($request);

        // set Content Languages header in the response | always return Content-Type application/json in the header
        $response->headers->set('Content-Type', $content_type);

        // if request doesn't contain in header accept = application/json. Return a warning in the response
        if (!str_contains($accept_header, $content_type)) {
            $warn_code = '199'; // https://www.iana.org/assignments/http-warn-codes/http-warn-codes.xhtml
            $warn_message = 'Missing request header [ accept = ' . $content_type . ' ] when calling a JSON API.';
            $response->headers->set('Warning', $warn_code . ' ' . $warn_message);
        }

        // return the response
        return $response;
    }
}
