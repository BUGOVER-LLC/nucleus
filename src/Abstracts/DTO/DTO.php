<?php

declare(strict_types=1);

namespace Nucleus\Abstracts\DTO;

use App\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Auth;

abstract class DTO implements Arrayable
{
    private ?object $user;

    private ?string $token;

    /**
     * Get the Authed user primary key value
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->getUser()->{$this->getUser()->getKeyName()};
    }

    /**
     * @return object|User|Authenticatable|null
     */
    public function getUser(): ?object
    {
        if ($this->user) {
            return $this->user;
        }

        $this->user = Auth::user();

        return $this->user;
    }

    public function setUser(object $user): static
    {
        $this->user = $user;

        return $this;
    }

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
