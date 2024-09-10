<?php

declare(strict_types=1);

namespace Nucleus\Traits;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use JetBrains\PhpStorm\Pure;

trait ModelTrait
{
    use HasResourceKeyTrait;
    use FactoryLocatorTrait;
    use ModelUUID;
    use FactoryLocatorTrait;
    use HasFactory;

    /**
     * @var string
     */
    protected string $map = '';

    /**
     * @return string
     */
    public static function map(): string
    {
        return (new static())->map;
    }

    /**
     * @return string
     */
    #[Pure] public function getMap(): string
    {
        return (new static())->map;
    }
}
