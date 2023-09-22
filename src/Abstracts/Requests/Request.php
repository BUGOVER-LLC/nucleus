<?php

declare(strict_types=1);

namespace Nucleus\Abstracts\Requests;

use Exception;
use Illuminate\Foundation\Http\FormRequest as LaravelRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Validator;
use Nucleus\Abstracts\Models\AuthModel as User;
use Nucleus\Exceptions\IncorrectIdException;
use Nucleus\Traits\HashIdTrait;
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
    use HashIdTrait;
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
     * check if a user has permission to perform an action.
     * User can set multiple permissions (separated with "|") and if the user has
     * any of the permissions, he will be authorized to proceed with this action.
     *
     * @param User|null $user
     *
     * @return  bool
     */
    public function hasAccess(User $user = null): bool
    {
        // if not in parameters, take from the request object {$this}
        $user = $user ?: $this->user();

        if ($user) {
            $autoAccessRoles = Config::get('nucleus.requests.allow-roles-to-access-all-routes');
            // there are some roles defined that will automatically grant access
            if (!empty($autoAccessRoles)) {
                $hasAutoAccessByRole = $user->hasAnyRole($autoAccessRoles);
                if ($hasAutoAccessByRole) {
                    return true;
                }
            }
        }

        // check if the user has any role / permission to access the route
        $hasAccess = array_merge(
            $this->hasAnyPermissionAccess($user),
            $this->hasAnyRoleAccess($user)
        );

        // allow access if user has access to any of the defined roles or permissions.
        return empty($hasAccess) || in_array(true, $hasAccess, true);
    }

    /**
     * @param $user
     *
     * @return  array
     */
    private function hasAnyPermissionAccess($user): array
    {
        if (!array_key_exists('permissions', $this->access) || !$this->access['permissions']) {
            return [];
        }

        $permissions = is_array($this->access['permissions']) ? $this->access['permissions'] :
            explode('|', $this->access['permissions']);

        return array_map(static function ($permission) use ($user) {
            return $user->hasPermissionTo($permission);
        }, $permissions);
    }

    /**
     * @param $user
     *
     * @return  array
     */
    private function hasAnyRoleAccess($user): array
    {
        if (!array_key_exists('roles', $this->access) || !$this->access['roles']) {
            return [];
        }

        $roles = is_array($this->access['roles']) ? $this->access['roles'] :
            explode('|', $this->access['roles']);

        return array_map(static function ($role) use ($user) {
            return $user->hasRole($role);
        }, $roles);
    }

    /**
     * Maps Keys in the Request.
     *
     * For example, ['data.attributes.name' => 'firstname'] would map the field [data][attributes][name] to [firstname].
     * Note that the old value (data.attributes.name) is removed the original request - this method manipulates the request!
     * Be sure you know what you do!
     *
     * @param array $fields
     * @throws IncorrectIdException
     */
    public function mapInput(array $fields): void
    {
        $data = $this->all();

        foreach ($fields as $oldKey => $newKey) {
            // the key to be mapped does not exist - skip it
            if (!Arr::has($data, $oldKey)) {
                continue;
            }

            // set the new field and remove the old one
            Arr::set($data, $newKey, Arr::get($data, $oldKey));
            Arr::forget($data, $oldKey);
        }

        // overwrite the initial request
        $this->replace($data);
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
     * @return Validator
     */
    public function validator(): Validator
    {
        $v = Validator::make($this->input(), $this->rules(), $this->messages(), $this->attributes());

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
     * Used from the `authorize` function if the Request class.
     * To call functions and compare their bool responses to determine
     * if the user can proceed with the request or not.
     *
     * @param array $functions
     *
     * @return  bool
     */
    protected function check(array $functions): bool
    {
        $orIndicator = '|';
        $returns = [];

        // iterate all functions in the array
        foreach ($functions as $function) {
            // in case the value doesn't contain a separator (single function per key)
            if (!strpos($function, $orIndicator)) {
                // simply call the single function and store the response.
                $returns[] = $this->{$function}();
            } else {
                // in case the value contains a separator (multiple functions per key)
                $orReturns = [];

                // iterate over each function in the key
                foreach (explode($orIndicator, $function) as $orFunction) {
                    // dynamically call each function
                    $orReturns[] = $this->{$orFunction}();
                }

                // if in_array returned `true` means at least one function returned `true` thus return `true` to allow access.
                // if in_array returned `false` means no function returned `true` thus return `false` to prevent access.
                // return single boolean for all the functions found inside the same key.
                $returns[] = in_array(true, $orReturns, true);
            }
        }

        // if in_array returned `true` means a function returned `false` thus return `false` to prevent access.
        // if in_array returned `false` means all functions returned `true` thus return `true` to allow access.
        // return the final boolean
        return !in_array(false, $returns, true);
    }

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
