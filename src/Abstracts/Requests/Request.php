<?php

declare(strict_types=1);

namespace Nucleus\Abstracts\Requests;

use Exception;
use Illuminate\Foundation\Http\FormRequest as LaravelRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Nucleus\Abstracts\Models\AuthModel as User;
use Nucleus\Exceptions\IncorrectIdException;
use Nucleus\Traits\SanitizerTrait;
use Nucleus\Traits\StateKeeperTrait;
use RuntimeException;

/**
 * Class Request
 *
 * A.K.A (app/Http/Requests/Request.php)
 *
 * @author  Mahmoud Zalt  <mahmoud@zalt.me>
 */
abstract class Request extends LaravelRequest
{
    use StateKeeperTrait;
    use SanitizerTrait;

    /**
     * @var string
     */
    private const VALIDATE_FLOAT_OR_INT = '/^(?=.)([+-]?([0-9]*)(\.([0-9]+))?)$/';

    /**
     * @var string
     */
    private const VALIDATE_STRING_OR_INT = '/^[a-z0-9 ]+$/i';

    /**
     * @var bool
     */
    protected bool $strict = true;

    /**
     * To be used mainly from unit tests.
     *
     * @param array $parameters
     * @param User|null $user
     * @param array $cookies
     * @param array $files
     * @param array $server
     *
     * @return  static
     */
    public static function injectData(
        array $parameters = [],
        User $user = null,
        array $cookies = [],
        array $files = [],
        array $server = []
    ): static {
        // if user is passed, will be returned when asking for the authenticated user using `\Auth::user()`
        if ($user) {
            $app = App::getInstance();
            $app['auth']->guard($driver = 'api')->setUser($user);
            $app['auth']->shouldUse($driver);
        }

        // For now doesn't matter which URI or Method is used.
        $request = parent::create('/', 'GET', $parameters, $cookies, $files, $server);

        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        return $request;
    }

    /**
     * This method mimics the $request->input() method but works on the "decoded" values
     *
     * @param $key
     * @param $default
     *
     * @return mixed
     * @throws IncorrectIdException
     */
    public function getInputByKey($key = null, $default = null): mixed
    {
        return data_get($this->all(), $key, $default);
    }

    /**
     * Overriding this function to modify the any user input before
     * applying the validation rules.
     *
     * @param null $keys
     *
     * @return  array
     * @throws IncorrectIdException
     */
    public function all($keys = null): array
    {
        $requestData = parent::all($keys);

        $requestData = $this->mergeUrlParametersWithRequestData($requestData);

        $requestData = $this->decodeHashedIdsBeforeValidation($requestData);

        return $requestData;
    }

    /**
     * apply validation rules to the ID's in the URL, since Laravel
     * doesn't validate them by default!
     *
     * Now you can use validation rules like this: `'id' => 'required|integer|exists:items,id'`
     *
     * @param array $requestData
     *
     * @return  array
     */
    private function mergeUrlParametersWithRequestData(array $requestData): array
    {
        if (isset($this->urlParameters) && !empty($this->urlParameters)) {
            foreach ($this->urlParameters as $param) {
                $requestData[$param] = $this->route($param);
            }
        }

        return $requestData;
    }

    /**
     * @return \Illuminate\Validation\Validator
     * @throws IncorrectIdException
     */
    public function validator(): \Illuminate\Validation\Validator
    {
        $v = Validator::make($this->all(), $this->rules(), $this->messages(), $this->attributes());

        if (method_exists(static::class, 'moreValidation')) {
            $this->moreValidation($v);
        }

        return $v;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    abstract public function rules(): array;

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    final public function messages(): array
    {
        return $this->errorMessages();
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    abstract public function errorMessages(): array;

    /**
     * Handle a passed validation attempt.
     *
     * @throws Exception
     */
    protected function passedValidation(): void
    {
        if (!$this->strict) {
            return;
        }

        if (!config('app.strict') || app()->isProduction()) {
            return;
        }

        $all_with_dots = Arr::dot($this->all());
        $validated_with_dots = Arr::dot($this->validated());

        $not_validated_fields = array_keys(array_diff_key($all_with_dots, $validated_with_dots));

        if (!empty($not_validated_fields)) {
            throw new RuntimeException(
                trans('validation.all_request_validation', ['fields' => implode(', ', $not_validated_fields)]),
                423
            );
        }

        $rules_with_dots = Arr::dot($this->rules());

        $empty_rules = array_filter($rules_with_dots, static function ($rule) {
            return empty($rule);
        });

        if (!empty($empty_rules)) {
            throw new RuntimeException(
                trans('validation.all_request_empty', ['fields' => implode(', ', array_keys($empty_rules))]),
                423
            );
        }

        parent::passedValidation();
    }

    /**
     * @return string
     */
    protected function getFloatOrInPattern(): string
    {
        return self::VALIDATE_FLOAT_OR_INT;
    }

    /**
     * @return string
     */
    protected function getStringOrIntPattern(): string
    {
        return self::VALIDATE_STRING_OR_INT;
    }
}
