<?php

declare(strict_types=1);

namespace Nucleus\Abstracts\Actions;

use Illuminate\Support\Facades\DB;

abstract class Action
{

    /**
     * @var string
     */
    protected string $ui;

    /**
     * @param ...$arguments
     * @return mixed
     */
    public function transactionalRun(...$arguments)
    {
        return DB::transaction(function () use ($arguments) {
            return static::run(...$arguments);
        });
    }

    /**
     * @return string
     */
    public function getUI(): string
    {
        return $this->ui;
    }

    /**
     * @param string $interface
     * @return $this
     */
    public function setUI(string $interface): static
    {
        $this->ui = $interface;

        return $this;
    }
}
