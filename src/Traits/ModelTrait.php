<?php

declare(strict_types=1);

namespace Nucleus\Traits;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use JetBrains\PhpStorm\Pure;
use Nucleus\Attributes\ModelEntity;
use ReflectionClass;
use Service\Repository\Contracts\EloquentRepositoryContract;

trait ModelTrait
{
    use HasResourceKeyTrait;
    use FactoryLocatorTrait;
    use ModelUUID;
    use HasUlids;
    use FactoryLocatorTrait;
    use HasFactory {
        HasFactory::newFactory insteadof FactoryLocatorTrait;
        FactoryLocatorTrait::newFactory insteadof HasFactory;
    }

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
    protected string $map = '';

    /**
     * @return string
     */
    #[Pure] public function getMap(): string
    {
        return (new static())->map;
    }

    public function flush(): void
    {
        $this->getModelRepositoryObject()->forgetCache();
    }

    public function getModelRepositoryObject(): EloquentRepositoryContract
    {
        return app($this->getModelRepositoryClass());
    }

    /**
     * @return string
     */
    public function getModelRepositoryClass(): string
    {
        $reflectionClass = new ReflectionClass(static::class);

        return collect($reflectionClass->getAttributes(ModelEntity::class))
            ->map(fn($attribute) => $attribute->getArguments())
            ->flatten()
            ->first();
    }

    /**
     * @return string
     */
    public static function map(): string
    {
        return (new static())->map;
    }
}
