<?php

declare(strict_types=1);

namespace Nucleus\Abstracts\Models;

use Illuminate\Foundation\Auth\User as LaravelAuthenticatableUser;
use Illuminate\Notifications\Notifiable;
use Nucleus\Attributes\ModelEntity;
use Nucleus\Traits\HasResourceKeyTrait;
use Nucleus\Traits\ModelTrait;

#[\AllowDynamicProperties]
#[ModelEntity()]
abstract class AuthModel extends LaravelAuthenticatableUser
{
    use HasResourceKeyTrait;
    use ModelTrait;
    use Notifiable;
}
