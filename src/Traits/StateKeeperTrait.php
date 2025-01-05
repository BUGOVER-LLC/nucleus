<?php

declare(strict_types=1);

namespace Nucleus\Traits;

use Nucleus\Abstracts\Request\Request;

trait StateKeeperTrait
{
    /**
     * Stores Data of any kind during the request life cycle.
     * This helps related Actions can share data from different steps.
     */
    public array $stateKeeperStates = [];

    /**
     * @param array $data
     * @return StateKeeperTrait|Request
     */
    public function keep(array $data = []): self
    {
        foreach ($data as $key => $value) {
            $this->stateKeeperStates[$key] = $value;
        }

        return $this;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function retrieve($key): mixed
    {
        return $this->stateKeeperStates[$key];
    }
}
