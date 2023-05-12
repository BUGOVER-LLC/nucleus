<?php

namespace Nucleus\Abstracts\Providers;

use Illuminate\Support\ServiceProvider as LaravelAppServiceProvider;
use Nucleus\Loaders\AliasesLoaderTrait;
use Nucleus\Loaders\ProvidersLoaderTrait;

abstract class MainServiceProvider extends LaravelAppServiceProvider
{
    use ProvidersLoaderTrait;
    use AliasesLoaderTrait;

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
