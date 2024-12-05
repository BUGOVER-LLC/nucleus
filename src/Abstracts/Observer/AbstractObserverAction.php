<?php

declare(strict_types=1);

namespace Nucleus\Abstracts\Observer;

use Illuminate\Database\Eloquent\Model;
use Nucleus\Abstracts\Models\AuthModel;

/**
 * Observer action
 */
abstract class AbstractObserverAction
{
    /**
     * @var Model
     */
    protected Model $model;

    /**
     * @var AuthModel|null
     */
    protected ?AuthModel $emitting;

    /**
     * @var string
     */
    protected string $method;

    /**
     * @param Model $model
     * @param string $method
     * @param object|null $emitting
     */
    public static function invoke(Model $model, string $method, ?object $emitting): void
    {
        (app(static::class))->setData($model, $method, $emitting);
    }

    /**
     * @param Model $model
     * @param string $method
     * @param AuthModel|null $emitting
     */
    private function setData(Model $model, string $method, ?AuthModel $emitting): void
    {
        $this->model = $model;
        $this->method = $method;
        $this->emitting = $emitting;
        $this->handle();
    }

    /**
     * @return void|mixed
     */
    abstract protected function handle();
}
