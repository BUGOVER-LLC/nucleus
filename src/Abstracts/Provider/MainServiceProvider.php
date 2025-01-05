<?php

declare(strict_types=1);

namespace Nucleus\Abstracts\Provider;

use Illuminate\Support\ServiceProvider as LaravelAppServiceProvider;
use Nucleus\Loaders\AliasesLoaderTrait;
use Nucleus\Loaders\ProvidersLoaderTrait;

abstract class MainServiceProvider extends LaravelAppServiceProvider
{
    use ProvidersLoaderTrait;
    use AliasesLoaderTrait;

    protected array $aliases = [];

    protected array $serviceProviders = [];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->loadServiceProviders();
        $this->loadAliases();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
    }
}
