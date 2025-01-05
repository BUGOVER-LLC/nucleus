<?php

declare(strict_types=1);

namespace Nucleus\Abstracts\Task;

use Nucleus\Contract\AbstractTaskContract;

abstract class Task implements AbstractTaskContract
{
    /**
     * @param mixed $context
     * @return mixed
     */
    final public function run(mixed $context): mixed
    {
        return $this->handle($context);
    }
}
