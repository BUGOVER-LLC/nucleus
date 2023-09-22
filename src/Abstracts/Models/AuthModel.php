<?php

declare(strict_types=1);

namespace Nucleus\Abstracts\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as LaravelAuthenticatableUser;
use Nucleus\Traits\FactoryLocatorTrait;
use Nucleus\Traits\HashedRouteBindingTrait;
use Nucleus\Traits\HashIdTrait;
use Nucleus\Traits\HasResourceKeyTrait;
use Nucleus\Traits\ModelTrait;

abstract class AuthModel extends LaravelAuthenticatableUser
{
    use HashIdTrait;
    use HashedRouteBindingTrait;
    use HasResourceKeyTrait;
    use HasFactory, FactoryLocatorTrait {
        FactoryLocatorTrait::newFactory insteadof HasFactory;
    }
    use ModelTrait;
}
