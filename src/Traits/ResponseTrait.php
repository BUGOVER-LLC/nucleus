<?php

declare(strict_types=1);

namespace Nucleus\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Nucleus\Abstracts\Controllers\ApiController;
use ReflectionClass;
use ReflectionException;

use function in_array;
use function is_array;

trait ResponseTrait
{
    protected array $metaData = [];

    /**
     * @param $data
     * @return ApiController|ResponseTrait
     */
    public function withMeta($data): self
    {
        $this->metaData = $data;

        return $this;
    }

    /**
     * @param $message
     * @param $status
     * @param array $headers
     * @param $options
     * @return JsonResponse
     */
    public function json($message, $status = 200, array $headers = [], $options = 0): JsonResponse
    {
        return new JsonResponse($message, $status, $headers, $options);
    }

    /**
     * @param $message
     * @param $status
     * @param array $headers
     * @param $options
     * @return JsonResponse
     */
    public function created($message = null, $status = 201, array $headers = [], $options = 0): JsonResponse
    {
        return new JsonResponse($message, $status, $headers, $options);
    }

    /**
     * @throws ReflectionException
     */
    public function deleted($responseArray = null): JsonResponse
    {
        if (!$responseArray) {
            return $this->accepted();
        }

        $id = $responseArray->getHashedKey();
        $class_name = (new ReflectionClass($responseArray))->getShortName();

        return $this->accepted([
            'message' => "$class_name ($id) Deleted Successfully.",
        ]);
    }

    /**
     * @param string|array $message
     * @param int $status
     * @param array $headers
     * @param int $options
     * @return JsonResponse
     */
    public function accepted(
        string|array $message = '',
        int $status = 202,
        array $headers = [],
        int $options = 0
    ): JsonResponse {
        return new JsonResponse($message, $status, $headers, $options);
    }

    /**
     * @param int $status
     * @return JsonResponse
     */
    public function noContent(int $status = 204): JsonResponse
    {
        return new JsonResponse(null, $status);
    }

    /**
     * @return array
     */
    protected function parseRequestedIncludes(): array
    {
        return explode(',', Request::get('include'));
    }

    /**
     * @param array $response_array
     * @param array $filters
     * @return array
     */
    private function filterResponse(array $response_array, array $filters): array
    {
        foreach ($response_array as $k => $v) {
            if (in_array($k, $filters, true)) {
                // we have found our element - so continue with the next one
                continue;
            }

            if (is_array($v)) {
                // it is an array - so go one step deeper
                $v = $this->filterResponse($v, $filters);
                if (empty($v)) {
                    // it is an empty array - delete the key as well
                    unset($response_array[$k]);
                } else {
                    $response_array[$k] = $v;
                }
            } elseif (!in_array($k, $filters, true)) {
                unset($response_array[$k]);
            }
        }

        return $response_array;
    }
}
