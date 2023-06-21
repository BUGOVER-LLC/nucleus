<?php

declare(strict_types=1);

namespace Nucleus\Abstracts\Transformers;

use ErrorException;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Laravel\Passport\Scope;
use Nucleus\Exceptions\CoreInternalErrorException;
use Nucleus\Exceptions\UnsupportedFractalIncludeException;

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
abstract class Transformer extends JsonResource
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

    public function nullableItem($data, $transformer, $resourceKey = null): Primitive|Item
    {
        if (is_null($data)) {
            return $this->primitive(null);
        }

        return $this->item($data, $transformer, $resourceKey = null);
    }

    public function item($data, $transformer, $resourceKey = null): Item
    {
        // set a default resource key if none is set
        if (!$resourceKey && $data) {
            $resourceKey = $data->getResourceKey();
        }

        return parent::item($data, $transformer, $resourceKey);
    }

    /**
     * @param string $class
     * @return $this
     */
    public function collectionClass(string $class = ''): Transformer
    {
        $this->collectionClass = $class;

        return $this;
    }

    /**
     * @throws CoreInternalErrorException
     * @throws UnsupportedFractalIncludeException
     */
    protected function callIncludeMethod(Scope $scope, $includeName, $data)
    {
        try {
            return parent::callIncludeMethod($scope, $includeName, $data);
        } catch (ErrorException $exception) {
            if (Config::get('nucleus.requests.force-valid-includes', true)) {
                throw new UnsupportedFractalIncludeException($exception->getMessage());
            }
        } catch (Exception $exception) {
            throw new CoreInternalErrorException($exception->getMessage());
        }
    }
}
