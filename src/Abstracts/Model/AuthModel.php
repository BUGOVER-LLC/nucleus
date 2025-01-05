<?php

declare(strict_types=1);

namespace Nucleus\Abstracts\Model;

use Illuminate\Foundation\Auth\User as LaravelAuthenticatableUser;
use Illuminate\Notifications\Notifiable;
use Nucleus\Attributes\ModelEntity;
use Nucleus\Contract\EntityContract;
use Nucleus\Traits\HasResourceKeyTrait;
use Nucleus\Traits\ModelTrait;

#[ModelEntity()]
abstract class AuthModel extends LaravelAuthenticatableUser implements EntityContract
{
    use HasResourceKeyTrait;
    use ModelTrait;
    use Notifiable;

    public const null|string UPDATED_AT = 'updatedAt';
    public const string CREATED_AT = 'createdAt';
    public const string DELETED_AT = 'deletedAt';

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var string
     */
    protected $keyType = 'string';

    /**
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';
}
