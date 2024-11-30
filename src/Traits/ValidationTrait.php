<?php

declare(strict_types=1);

namespace Nucleus\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use function is_array;

trait ValidationTrait
{
    /**
     * Extend the default Laravel validation rules.
     */
    public function extendValidationRules(): void
    {
        // Validate String contains no space.
        Validator::extend('no_spaces', static function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^\S*$/u', $value);
        }, 'String should not contain space.');

        // Validate composite unique ID.
        // Usage: unique_composite:table,this-attribute-column,the-other-attribute-column
        // Example:    'values'               => 'required|unique_composite:item_variant_values,value,item_variant_name_id',
        //             'item_variant_name_id' => 'required',
        Validator::extend('unique_composite', function ($attribute, $value, $parameters, $validator) {
            $QB = DB::table($parameters[0]);

            $QB = is_array($value) ? $QB->whereIn($parameters[1], $value) : $QB->where(
                $parameters[1],
                $value
            );

            $QB->where($parameters[2], $validator->getData()[$parameters[2]]);

            return $QB->get()->isEmpty();
        }, 'Duplicated record. This record has composite ID and it must be unique.');
    }
}
