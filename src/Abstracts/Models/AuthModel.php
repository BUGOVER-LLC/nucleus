<?php

declare(strict_types=1);

namespace Nucleus\Abstracts\Models;

use Illuminate\Foundation\Auth\User as LaravelAuthenticatableUser;
use Nucleus\Traits\HasResourceKeyTrait;
use Nucleus\Traits\ModelTrait;

abstract class AuthModel extends LaravelAuthenticatableUser
{
    use HasResourceKeyTrait;
    use ModelTrait;
}
