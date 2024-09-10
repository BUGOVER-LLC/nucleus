<?php

declare(strict_types=1);

namespace Nucleus\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Nucleus\Abstracts\Providers\MainServiceProvider as AbstractMainServiceProvider;
use Nucleus\Foundation\Nuclear;
use Nucleus\Loaders\AutoLoaderTrait;
use Nucleus\Traits\ValidationTrait;
use ReflectionException;

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
        $this->app->bind('Nuclear', Nuclear::class);
        $this->app->alias(Nuclear::class, 'Nuclear');

        parent::register();

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
            ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'configs' . DIRECTORY_SEPARATOR . 'nucleus.php';

        if (File::exists($config)) {
            $this->mergeConfigFrom($config, 'nucleus');
            $this->publishes(['nucleus']);
        }

        $this->runLoadersBoot();

        Schema::defaultStringLength(191);

        $this->extendValidationRules();
    }
}
