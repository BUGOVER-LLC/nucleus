<?php

declare(strict_types=1);

namespace Nucleus\Abstracts\Criterias;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Service\Repository\Contracts\BaseCriteriaContract;

interface CriteriaInterface extends BaseCriteriaContract
{
}
