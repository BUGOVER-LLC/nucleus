<?php

declare(strict_types=1);

namespace Nucleus\Abstracts\Value;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Nucleus\Contract\HasResourceKey;
use Nucleus\Traits\HasResourceKeyTrait;

abstract class Value implements HasResourceKey, CastsAttributes
{
    use HasResourceKeyTrait;
}
