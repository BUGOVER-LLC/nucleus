<?php

namespace Nucleus\Abstracts\Controllers;

use Nucleus\Abstracts\Controllers\Controller;
use Nucleus\Traits\ResponseTrait;

abstract class ApiController extends Controller
{
    use ResponseTrait;

    /**
     * The type of this controller. This will be accessibly mirrored in the Actions.
     * Giving each Action the ability to modify it's internal business logic based on the UI type that called it.
     */
    public string $ui = 'api';
}
