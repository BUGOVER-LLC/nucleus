<?php

declare(strict_types=1);

namespace Nucleus\Abstracts\Repositories;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Service\Repository\Exceptions\RepositoryException;
use Service\Repository\Repositories\EloquentRepository;

abstract class Repository extends EloquentRepository
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
     * Creates a new QueryBuilder instance that is prepopulated for this entity name.
     *
     * @param ?string $alias
     * @param array $columns
     * @param null $indexBy The index for the from.
     *
     * @return QueryBuilder
     * @throws BindingResolutionException
     * @throws RepositoryException
     */
    public function createQueryBuilder(
        string $alias = null,
        array $columns = [],
        $indexBy = null
    ): QueryBuilder {
        return DB::table($this->createModel()->getTable(), $alias)->select($columns)->from(
            $this->getModel()->getTable(),
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
        return $this->getModel()::query()->select($columns)->from($this->getModel()->getTable(), $alias);
    }
}
