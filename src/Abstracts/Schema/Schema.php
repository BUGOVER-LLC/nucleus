<?php

declare(strict_types=1);

namespace Nucleus\Abstracts\Schema;

use Illuminate\Contracts\Support\Arrayable;

abstract class Schema implements Arrayable
{
    final public function toArray(): array
    {
        return collect(get_object_vars($this))->toArray();
    }
}
