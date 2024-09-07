<?php

declare(strict_types=1);

namespace Nucleus\Abstracts\Resources;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

/**
 * Class BaseResource
 * @package Src\Http\Resources
 * @property JsonResource $collection_data
 * @method Collection get(string $string, $default = null)
 * @method Collection gets(string $string, $default = null)
 * @method Collection stringSerialize(string $name)
 * @method LengthAwarePaginator currentPage()
 * @method LengthAwarePaginator url($url)
 * @method LengthAwarePaginator firstItem()
 * @method LengthAwarePaginator lastPage()
 * @method LengthAwarePaginator nextPageUrl()
 * @method LengthAwarePaginator path()
 * @method LengthAwarePaginator perPage()
 * @method LengthAwarePaginator previousPageUrl()
 * @method LengthAwarePaginator lastItem()
 * @method LengthAwarePaginator total()
 * @method LengthAwarePaginator hasPages()
 * @method LengthAwarePaginator items()
 */
abstract class Resource extends JsonResource
{
    /**
     * @var string|JsonResource
     */
    protected $collectionClass = '';

    /**
     * Set the string that should wrap the outer-most resource array.
     *
     * @param string $value
     * @return void
     */
    public static function wrap($value): void
    {
        if (property_exists(self::class, 'wrap')) {
            JsonResource::wrap(static::$wrap);
        }

        JsonResource::wrap(null);
    }

    /**
     * @param string $class
     * @return $this
     */
    public function collectionClass(string $class = ''): static
    {
        $this->collectionClass = $class;

        return $this;
    }
}
