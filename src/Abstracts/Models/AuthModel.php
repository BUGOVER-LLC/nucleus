<?php

declare(strict_types=1);

namespace Nucleus\Abstracts\Models;

use AllowDynamicProperties;
use Illuminate\Foundation\Auth\User as LaravelAuthenticatableUser;
use Illuminate\Notifications\Notifiable;
use Nucleus\Attributes\ModelEntity;
use Nucleus\Contract\EntityContract;
use Nucleus\Traits\HasResourceKeyTrait;
use Nucleus\Traits\ModelTrait;

#[AllowDynamicProperties]
#[ModelEntity()]
abstract class AuthModel extends LaravelAuthenticatableUser implements EntityContract
{
    use HasResourceKeyTrait;
    use ModelTrait;
    use Notifiable;
}
