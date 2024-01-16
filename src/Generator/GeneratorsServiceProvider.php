<?php

declare(strict_types=1);

namespace Nucleus\Generator;

use Illuminate\Support\ServiceProvider;
use Nucleus\Generator\Commands\ActionGenerator;
use Nucleus\Generator\Commands\ConfigurationGenerator;
use Nucleus\Generator\Commands\ContainerApiGenerator;
use Nucleus\Generator\Commands\ContainerGenerator;
use Nucleus\Generator\Commands\ContainerWebGenerator;
use Nucleus\Generator\Commands\ControllerGenerator;
use Nucleus\Generator\Commands\DumpAutoload;
use Nucleus\Generator\Commands\EventGenerator;
use Nucleus\Generator\Commands\EventListenerGenerator;
use Nucleus\Generator\Commands\ExceptionGenerator;
use Nucleus\Generator\Commands\JobGenerator;
use Nucleus\Generator\Commands\MailGenerator;
use Nucleus\Generator\Commands\MiddlewareGenerator;
use Nucleus\Generator\Commands\MigrationGenerator;
use Nucleus\Generator\Commands\ModelFactoryGenerator;
use Nucleus\Generator\Commands\ModelGenerator;
use Nucleus\Generator\Commands\NotificationGenerator;
use Nucleus\Generator\Commands\PolicyGenerator;
use Nucleus\Generator\Commands\ReadmeGenerator;
use Nucleus\Generator\Commands\RepositoryGenerator;
use Nucleus\Generator\Commands\RequestGenerator;
use Nucleus\Generator\Commands\RouteGenerator;
use Nucleus\Generator\Commands\SeederGenerator;
use Nucleus\Generator\Commands\ServiceProviderGenerator;
use Nucleus\Generator\Commands\SubActionGenerator;
use Nucleus\Generator\Commands\TaskGenerator;
use Nucleus\Generator\Commands\TestFunctionalTestGenerator;
use Nucleus\Generator\Commands\TestTestCaseGenerator;
use Nucleus\Generator\Commands\TestUnitTestGenerator;
use Nucleus\Generator\Commands\ResourceGenerator;
use Nucleus\Generator\Commands\ValueGenerator;

class GeneratorsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands($this->getGeneratorCommands());
        }
    }

    /**
     * @return string[]
     */
    private function getGeneratorCommands(): array
    {
        // add your generators here
        return [
            ActionGenerator::class,
            ConfigurationGenerator::class,
            ContainerGenerator::class,
            ContainerApiGenerator::class,
            ContainerWebGenerator::class,
            ControllerGenerator::class,
            DumpAutoload::class,
            EventGenerator::class,
            EventListenerGenerator::class,
            ExceptionGenerator::class,
            JobGenerator::class,
            ModelFactoryGenerator::class,
            MailGenerator::class,
            MiddlewareGenerator::class,
            MigrationGenerator::class,
            ModelGenerator::class,
            NotificationGenerator::class,
            PolicyGenerator::class,
            ReadmeGenerator::class,
            RepositoryGenerator::class,
            RequestGenerator::class,
            RouteGenerator::class,
            SeederGenerator::class,
            ServiceProviderGenerator::class,
            SubActionGenerator::class,
            TestFunctionalTestGenerator::class,
            TestTestCaseGenerator::class,
            TestUnitTestGenerator::class,
            TaskGenerator::class,
            ResourceGenerator::class,
            ValueGenerator::class,
        ];
    }
}
