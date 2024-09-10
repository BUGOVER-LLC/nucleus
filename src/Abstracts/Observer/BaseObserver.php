<?php

declare(strict_types=1);

namespace Nucleus\Abstracts\Observer;

use Illuminate\Database\Eloquent\Model;

/**
 *
 */
abstract class BaseObserver
{
    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public bool $afterCommit = true;

    /**
     * @param Model $model
     * @return void
     */
    abstract public function created(Model $model): void;

    /**
     * @param Model $model
     * @return void
     */
    abstract public function updated(Model $model): void;

    /**
     * @param Model $model
     * @return void
     */
    abstract public function deleted(Model $model): void;

    /**
     * @param Model $model
     * @return void
     */
    public function saved(Model $model): void
    {
        // @todo saved body
    }

    /**
     * @param Model $model
     * @return void
     */
    public function creating(Model $model)
    {
        // @todo creating body
    }

    /**
     * @param Model $model
     * @return void
     */
    public function updating(Model $model)
    {
        // @todo updating body
    }

    /**
     * @param Model $model
     * @return void
     */
    public function saving(Model $model)
    {
        // @todo saving body
    }

    /**
     * @param Model $model
     * @return void
     */
    public function deleting(Model $model)
    {
        // @todo deleting body
    }

    /**
     * @param Model $model
     * @return void
     */
    public function restoring(Model $model): void
    {
        // @todo restoring body
    }

    /**
     * @param Model $model
     * @return void
     */
    public function restored(Model $model): void
    {
        // @todo restored body
    }

    /**
     * @param Model $model
     * @return void
     */
    public function retrieved(Model $model): void
    {
        // @todo retrieved body
    }

    /**
     * @param Model $model
     * @return void
     */
    public function forceDeleted(Model $model): void
    {
        // @todo restored body
    }

    /**
     * @param Model $model
     * @return void
     */
    public function replicating(Model $model): void
    {
        // @todo replicating body
    }

    /**
     * @param Model $model
     * @return void
     */
    public function trashed(Model $model): void
    {
        // @todo trashed body
    }
}
