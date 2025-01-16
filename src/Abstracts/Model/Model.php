<?php

declare(strict_types=1);

namespace Nucleus\Abstracts\Model;

use Illuminate\Database\Eloquent\Model as LaravelEloquentModel;
use Nucleus\Attributes\ModelEntity;
use Nucleus\Contract\EntityContract;
use Nucleus\Contract\EntityMapContract;
use Nucleus\Contract\HasResourceKey;
use Nucleus\Traits\EntityTrait;

#[ModelEntity()]
abstract class Model extends LaravelEloquentModel implements EntityContract, EntityMapContract, HasResourceKey
{
    use EntityTrait;

    public const null|string UPDATED_AT = 'updated_at';
    public const string CREATED_AT = 'created_at';
    public const string DELETED_AT = 'deleted_at';

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

    /**
     * @var string
     */
    protected string $map = '';

    /**
     * @var string
     */
    protected string $resourceKey = '';
}
