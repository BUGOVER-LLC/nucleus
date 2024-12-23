<?php

declare(strict_types=1);

namespace Nucleus\Generator\Commands;

use Nucleus\Generator\GeneratorCommand;
use Nucleus\Generator\Interfaces\ComponentsGenerator;
use Symfony\Component\Console\Input\InputOption;

class ObserverGenerator extends GeneratorCommand implements ComponentsGenerator
{
    /**
     * User required/optional inputs expected to be passed while calling the command.
     * This is a replacement of the `getArguments` function "which reads whenever it's called".
     *
     * @var  array
     */
    public array $inputs = [
        ['observer', null, InputOption::VALUE_OPTIONAL, 'Generate the corresponding Observer for this Model?'],
    ];

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'nucleus:generate:observer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Model observer';

    /**
     * The type of class being generated.
     */
    protected string $fileType = 'Observer';

    /**
     * The structure of the file path.
     */
    protected string $pathStructure = '{section-name}/{container-name}/Observer/*';

    /**
     * The structure of the file name.
     */
    protected string $nameStructure = '{file-name}';

    /**
     * The name of the stub file.
     */
    protected string $stubName = 'observer/observer.stub';

    #[\Override] public function getUserInputs(): ?array
    {

    }
}
