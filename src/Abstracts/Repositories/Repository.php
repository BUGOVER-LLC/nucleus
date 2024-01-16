<?php

declare(strict_types=1);

namespace Nucleus\Abstracts\Repositories;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Nucleus\Abstracts\Models\AuthModel;
use Nucleus\Abstracts\Models\Model;

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

    /**
     * @param Model|AuthModel $model
     */
    public function __construct(private readonly Model|AuthModel $model)
    {
    }

    /**
     * Creates a new QueryBuilder instance that is prepopulated for this entity name.
     *
     * @param ?string $alias
     * @param array $columns
     * @param null $indexBy The index for the from.
     *
     * @return QueryBuilder
     */
    public function createQueryBuilder(
        string $alias = null,
        array $columns = [],
        $indexBy = null
    ): QueryBuilder {
        return DB::table($this->model->getTable(), $alias)->select($columns)->from(
            $this->model->getTable(),
            $alias
        );
    }

    /**
     * Creates a new QueryBuilder instance that is prepopulated for this entity name.
     *
     * @param ?string $alias
     * @param array $columns
     * @param null $indexBy The index for the from.
     *
     * @return EloquentBuilder
     */
    public function createModelBuilder(
        string $alias = null,
        array $columns = [],
        $indexBy = null
    ): EloquentBuilder {
        return $this->model::query()->select($columns)->from($this->model->getTable(), $alias);
    }
}
