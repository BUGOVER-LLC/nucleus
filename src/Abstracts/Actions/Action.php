<?php

namespace Nucleus\Abstracts\Actions;

use Illuminate\Support\Facades\DB;
use Nucleus\Traits\HasRequestCriteriaTrait;

abstract class Action
{
    use HasRequestCriteriaTrait;

    protected string $ui;

    public function transactionalRun(...$arguments)
    {
        return DB::transaction(function () use ($arguments) {
            return static::run(...$arguments);
        });
    }

    public function getUI(): string
    {
        return $this->ui;
    }

    public function setUI(string $interface): static
    {
        $this->ui = $interface;

        return $this;
    }
}
