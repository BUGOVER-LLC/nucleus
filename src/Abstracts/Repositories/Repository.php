<?php

namespace Nucleus\Abstracts\Repositories;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Nucleus\Abstracts\Models\Model;
use Nucleus\Abstracts\Models\UserModel;

abstract class Repository
{
    /**
     * Define the maximum amount of entries per page that is returned.
     * Set to 0 to "disable" this feature
     */
    protected int $maxPaginationLimit = 0;
    /**
     * Define the maximum amount of entries per page that is returned.
     * Set to 0 to "disable" this feature
     */
    protected ?bool $allowDisablePagination = null;

    public function __construct(private readonly Model|UserModel $model)
    {
    }

    /**
     * Creates a new QueryBuilder instance that is prepopulated for this entity name.
     *
     * @param string $alias
     * @param string|null $indexBy The index for the from.
     *
     * @return Builder
     */
    public function createQueryBuilder($alias, $indexBy = null): Builder
    {
        return DB::table($this->model->getTable(), $alias)->select()->from($this->model->getTable(), $alias)->useIndex(
                $indexBy
            );
    }

    /**
     * Creates a new QueryBuilder instance that is prepopulated for this entity name.
     *
     * @param string $alias
     * @param string|null $indexBy The index for the from.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function createModelBuilder($alias, $indexBy = null): \Illuminate\Database\Eloquent\Builder
    {
        return $this->model::query()->select()->from($this->model->getTable(), $alias)->useIndex($indexBy);
    }
}
