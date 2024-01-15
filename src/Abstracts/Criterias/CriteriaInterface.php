<?php

declare(strict_types=1);

namespace Nucleus\Abstracts\Criterias;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

interface CriteriaInterface
{
    /**
     * @return EloquentBuilder|QueryBuilder
     */
    public function apply(): EloquentBuilder|QueryBuilder;
}
