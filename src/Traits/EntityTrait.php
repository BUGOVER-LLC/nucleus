<?php

declare(strict_types=1);

namespace Nucleus\Traits;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use JetBrains\PhpStorm\Pure;

trait EntityTrait
{
    use EntityMapTrait;
    use EntityRepositoryTrait;
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
     * @return mixed
     */
    public static function getTableName(): string
    {
        return (new static())->getTable();
    }

    /////////////////////////////////////////////////////STATUS, TYPE, CLASS///////////////////////////////////////////

    /**
     * @return mixed
     */
    #[Pure] public static function getPrimaryName(): string
    {
        return (new static())->getKeyName();
    }

    /**
     * @return mixed
     */
    #[Pure] public static function getFillables(): array
    {
        return (new static())->getFillable();
    }
}
