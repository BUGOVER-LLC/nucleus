<?php

declare(strict_types=1);

namespace Nucleus\Abstracts\Providers;

use Illuminate\Support\ServiceProvider as LaravelAppServiceProvider;
use Nucleus\Abstracts\Models\AuthModel;
use Nucleus\Abstracts\Models\Model;
use Nucleus\Contract\EntityContract;
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

        $this->app->bind(EntityContract::class, Model::class);
        $this->app->bind(EntityContract::class, AuthModel::class);
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
