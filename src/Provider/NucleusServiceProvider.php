<?php

declare(strict_types=1);

namespace Nucleus\Provider;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Nucleus\Abstracts\Provider\MainServiceProvider as AbstractMainServiceProvider;
use Nucleus\Foundation\Nuclear;
use Nucleus\Loaders\AutoLoaderTrait;
use Nucleus\Traits\ValidationTrait;

class NucleusServiceProvider extends AbstractMainServiceProvider
{
    use AutoLoaderTrait;
    use ValidationTrait;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        parent::register();

        $this->app->bind('Nuclear', Nuclear::class);
        $this->app->alias(Nuclear::class, 'Nuclear');

        $this->runLoaderRegister();
    }

    /**
     * @return void
     */
    public function boot(): void
    {
        parent::boot();

        $config =
            \dirname(
                __DIR__
            ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'nucleus.php';

        if (File::exists($config)) {
            $this->mergeConfigFrom($config, 'nucleus');
            $this->publishes(['nucleus']);
        }

        $this->runLoadersBoot();

        Schema::defaultStringLength(191);
        Schema::morphUsingUlids();

        $this->extendValidationRules();
    }
}
