<?php

declare(strict_types=1);

namespace Nucleus\Abstracts\Value;

use Nucleus\Contract\HasResourceKey;
use Nucleus\Traits\HasResourceKeyTrait;

abstract class Value implements HasResourceKey
{
    use HasResourceKeyTrait;
}
