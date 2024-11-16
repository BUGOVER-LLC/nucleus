<?php

declare(strict_types=1);

namespace Nucleus\Loaders;

use Illuminate\Support\Collection;

trait SupportLoader
{
    protected function collectRegister(): void
    {
        Collection::macro('recToRec', function () {
            return $this->map(function ($value) {
                if (\is_array($value) || \is_object($value)) {
                    return collect($value)->recToRec();
                }

                return $value;
            });
        });
    }
}
