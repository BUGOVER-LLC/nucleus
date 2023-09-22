<?php

declare(strict_types=1);

namespace Nucleus\Abstracts\Models;

use Illuminate\Database\Eloquent\Model as LaravelEloquentModel;
use Nucleus\Traits\ModelTrait;

abstract class Model extends LaravelEloquentModel
{
    use ModelTrait;
}
