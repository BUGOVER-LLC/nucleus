<?php

declare(strict_types=1);

namespace Nucleus\Abstracts\Repositories;

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

    public function __construct(private readonly Model|AuthModel $model)
    {
    }

    /**
     * Creates a new QueryBuilder instance that is prepopulated for this entity name.
     *
     * @param string $alias
     * @param null $indexBy The index for the from.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function createQueryBuilder($alias, array $columns = [], $indexBy = null): \Illuminate\Database\Query\Builder
    {
        return DB::table($this->model->getTable(), $alias)->select($columns)->from(
            $this->model->getTable(),
            $alias
        )->useIndex(
            $indexBy
        );
    }

    /**
     * Creates a new QueryBuilder instance that is prepopulated for this entity name.
     *
     * @param ?string $alias
     * @param ?string $indexBy The index for the from.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function createModelBuilder(
        $alias = null,
        array $columns = [],
        $indexBy = null
    ): \Illuminate\Database\Eloquent\Builder {
        return $this->model::query()->select($columns)->from($this->model->getTable(), $alias)->useIndex($indexBy);
    }
}
