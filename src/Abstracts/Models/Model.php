<?php

declare(strict_types=1);

namespace Nucleus\Abstracts\Models;

use Illuminate\Database\Eloquent\Model as LaravelEloquentModel;
use Nucleus\Attributes\ModelEntity;
use Nucleus\Contract\EntityContract;
use Nucleus\Traits\ModelTrait;

#[ModelEntity()]
abstract class Model extends LaravelEloquentModel implements EntityContract
{
    use ModelTrait;

    public const null|string UPDATED_AT = 'updatedAt';
    public const string CREATED_AT = 'createdAt';
    public const string DELETED_AT = 'deletedAt';

    /* @noinspection PhpUnused */
    /**
     * @var bool
     */
    public $incrementing = false;

    /* @noinspection PhpUnused */
    /**
     * @var string
     */
    protected $keyType = 'string';

    /**
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';
}
