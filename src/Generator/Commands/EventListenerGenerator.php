<?php

namespace Nucleus\Generator\Commands;

use Nucleus\Generator\GeneratorCommand;
use Nucleus\Generator\Interfaces\ComponentsGenerator;
use Symfony\Component\Console\Input\InputOption;

class EventListenerGenerator extends GeneratorCommand implements ComponentsGenerator
{
    /**
     * User required/optional inputs expected to be passed while calling the command.
     * This is a replacement of the `getArguments` function "which reads whenever it's called".
     *
     * @var  array
     */
    public array $inputs = [
        ['event', null, InputOption::VALUE_OPTIONAL, 'The Event to generate this Listener for'],
    ];
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'nucleus:generate:listener';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Event Listener class';
    /**
     * The type of class being generated.
     */
    protected string $fileType = 'Listener';
    /**
     * The structure of the file path.
     */
    protected string $pathStructure = '{section-name}/{container-name}/Listener/*';
    /**
     * The structure of the file name.
     */
    protected string $nameStructure = '{file-name}';
    /**
     * The name of the stub file.
     */
    protected string $stubName = 'listeners/listener.stub';

    public function getUserInputs(): ?array
    {
        $event = $this->checkParameterOrAsk('event', 'Enter the name of the Event to generate this Listener for');

        $this->printInfoMessage('!!! Do not forget to register the Event and/or Event Listener !!!');

        return [
            'path-parameters' => [
                'section-name' => $this->sectionName,
                'container-name' => $this->containerName,
            ],
            'stub-parameters' => [
                'section-name' => $this->sectionName,
                'container-name' => $this->containerName,
                'class-name' => $this->fileName,
                'model' => $event,
            ],
            'file-parameters' => [
                'file-name' => $this->fileName,
            ],
        ];
    }
}
