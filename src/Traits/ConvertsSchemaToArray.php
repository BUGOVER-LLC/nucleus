<?php

declare(strict_types=1);

namespace Nucleus\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Nucleus\Abstracts\Schema\Schema;

trait ConvertsSchemaToArray
{
    private static mixed $additionalData;

    public static function schemas(array|Collection $collection): ?array
    {
        return collect($collection)->map(static fn($item) => static::schema($item))->toArray();
    }

    public function toArray($request): array|string
    {
        return $this
            ->toSchema($request)
            ->toArray();
    }

    /**
     * @param Request $request
     * @return Schema|string
     */
    abstract public function toSchema(Request $request): Schema|string;

    /**
     * @param $resource
     * @return Schema|null
     */
    public static function schema($resource): ?Schema
    {
        return $resource ? (new static($resource))->toSchema(request()) : null;
    }

    /**
     * @param mixed $data
     * @return static|string
     */
    public static function setAdditionalData(mixed $data): static|string
    {
        static::$additionalData = $data;

        return static::class;
    }

    /**
     * @return mixed
     */
    public function getAdditionallData(): mixed
    {
        return static::$additionalData;
    }
}
