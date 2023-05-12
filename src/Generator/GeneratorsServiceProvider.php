<?php

declare(strict_types=1);

namespace Nucleus\Generator;

use Illuminate\Support\ServiceProvider;
use Nucleus\src\Generator\Commands\ActionGenerator;
use Nucleus\src\Generator\Commands\ConfigurationGenerator;
use Nucleus\src\Generator\Commands\ContainerApiGenerator;
use Nucleus\src\Generator\Commands\ContainerGenerator;
use Nucleus\Generator\Commands\ContainerWebGenerator;
use Nucleus\src\Generator\Commands\ControllerGenerator;
use Nucleus\Generator\Commands\EventGenerator;
use Nucleus\src\Generator\Commands\EventListenerGenerator;
use Nucleus\Generator\Commands\ExceptionGenerator;
use Nucleus\src\Generator\Commands\JobGenerator;
use Nucleus\src\Generator\Commands\MailGenerator;
use Nucleus\src\Generator\Commands\MiddlewareGenerator;
use Nucleus\src\Generator\Commands\MigrationGenerator;
use Nucleus\src\Generator\Commands\ModelFactoryGenerator;
use Nucleus\Generator\Commands\ModelGenerator;
use Nucleus\src\Generator\Commands\NotificationGenerator;
use Nucleus\src\Generator\Commands\PolicyGenerator;
use Nucleus\src\Generator\Commands\ReadmeGenerator;
use Nucleus\src\Generator\Commands\RepositoryGenerator;
use Nucleus\Generator\Commands\RequestGenerator;
use Nucleus\Generator\Commands\RouteGenerator;
use Nucleus\Generator\Commands\SeederGenerator;
use Nucleus\Generator\Commands\ServiceProviderGenerator;
use Nucleus\Generator\Commands\SubActionGenerator;
use Nucleus\src\Generator\Commands\TaskGenerator;
use Nucleus\src\Generator\Commands\TestFunctionalTestGenerator;
use Nucleus\Generator\Commands\TestTestCaseGenerator;
use Nucleus\Generator\Commands\TestUnitTestGenerator;
use Nucleus\src\Generator\Commands\TransformerGenerator;
use Nucleus\src\Generator\Commands\ValueGenerator;

class GeneratorsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
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
            TransformerGenerator::class,
            ValueGenerator::class,
        ];
    }
}
