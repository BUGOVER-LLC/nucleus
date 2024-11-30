<?php

declare(strict_types=1);

namespace Nucleus\Abstracts\DTO;

use Illuminate\Contracts\Support\Arrayable;

abstract class DTO implements Arrayable
{
    private ?string $token;

    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return $this
     */
    public function setToken(string $token): static
    {
        $this->token = $token;

        return $this;
    }

    public function toArray()
    {
        return collect(get_object_vars($this))->toArray();
    }

    /**
     * @param string $key
     * @param mixed $values
     * @return void
     */
    public function append(string $key, mixed $values): void
    {
        $this->{$key} = $values;
    }
}
