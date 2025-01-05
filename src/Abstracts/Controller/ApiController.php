<?php

declare(strict_types=1);

namespace Nucleus\Abstracts\Controller;

use Nucleus\Traits\ResponseTrait;
use OpenApi\Attributes\Info;
use OpenApi\Attributes\PathItem;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Server;

#[
    Info(
        version: '0.1',
        description: '
    # Authorization
      - accessToken** valid for 30 days
      - refreshToken** valid for 90 days
      - accessUuid** valid unlimint user sync.
        ',
        title: 'API main service'
    )
]
#[PathItem(path: '/api/resource.json', servers: [
    new Server(
        url: 'https://example.com/api/v1'
    ),
]), Response(response: '200', description: 'An example resource')]
abstract class ApiController extends Controller
{
    use ResponseTrait;

    /**
     * The type of this controller. This will be accessibly mirrored in the Actions.
     * Giving each Action the ability to modify it's internal business logic based on the UI type that called it.
     */
    public string $ui = 'api';
}
