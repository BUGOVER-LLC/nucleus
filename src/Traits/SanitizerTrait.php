<?php

declare(strict_types=1);

namespace Nucleus\Traits;

use Illuminate\Support\Arr;
use Nucleus\Exceptions\IncorrectIdException;

trait SanitizerTrait
{
    /**
     * Sanitizes the data of a request. This is a superior version of php built-in array_filter() as it preserves
     * FALSE and NULL values as well.
     *
     * @param array $fields a list of fields to be checked in the Dot-Notation (e.g., ['data.name', 'data.description'])
     *
     * @return array an array containing the values if the field was present in the request and the intersection array
     * @throws IncorrectIdException
     */
    public function sanitizeInput(array $fields): array
    {
        $data = $this->all();

        $input_as_array = [];
        $fields_with_defaultValue = [];

        // create a multidimensional array based on $fields
        // which was submitted as DOT notation (e.g., data.name)
        foreach ($fields as $key => $value) {
            if (is_string($key)) {
                // save fields with default values
                $fields_with_defaultValue[$key] = $value;
                Arr::set($input_as_array, $key, $value);
            } else {
                Arr::set($input_as_array, $value, true);
            }
        }

        // check, if the keys exist in both arrays
        $data = $this->recursiveArrayIntersectKey($data, $input_as_array);

        // set default values if key doesn't exist
        foreach ($fields_with_defaultValue as $key => $value) {
            $data = Arr::add($data, $key, $value);
        }

        return $data;
    }

    /**
     * Recursively intersects 2 arrays based on their keys.
     *
     * @param array $a first array (that keeps the values)
     * @param array $b second array to be compared with
     *
     * @return array an array containing all keys that are present in $a and $b. Only values from $a are returned
     */
    private function recursiveArrayIntersectKey(array $a, array $b): array
    {
        $a = array_intersect_key($a, $b);

        foreach ($a as $key => &$value) {
            if (\is_array($value) && \is_array($b[$key])) {
                $value = $this->recursiveArrayIntersectKey($value, $b[$key]);
            }
        }

        return $a;
    }
}
