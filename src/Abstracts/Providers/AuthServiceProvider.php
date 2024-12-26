<?php

declare(strict_types=1);

namespace Nucleus\Abstracts\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as LaravelAuthServiceProvider;

abstract class AuthServiceProvider extends LaravelAuthServiceProvider
{
    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerPolicies();
        //
    }
}
