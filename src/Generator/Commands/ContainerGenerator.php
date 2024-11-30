<?php

declare(strict_types=1);

namespace Nucleus\Generator\Commands;

use Illuminate\Support\Str;
use Nucleus\Generator\GeneratorCommand;
use Nucleus\Generator\Interfaces\ComponentsGenerator;
use Symfony\Component\Console\Input\InputOption;

class ContainerGenerator extends GeneratorCommand implements ComponentsGenerator
{
    /**
     * User required/optional inputs expected to be passed while calling the command.
     * This is a replacement of the `getArguments` function "which reads whenever it's called".
     */
    public array $inputs = [
        ['ui', null, InputOption::VALUE_OPTIONAL, 'The user-interface to generate the Controller for.'],
    ];
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'nucleus:generate:container';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a Container for nucleus from scratch';
    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected string $fileType = 'Container';
    /**
     * The structure of the file path.
     *
     * @var  string
     */
    protected string $pathStructure = '{section-name}/{container-name}/*';
    /**
     * The structure of the file name.
     *
     * @var  string
     */
    protected string $nameStructure = '{file-name}';
    /**
     * The name of the stub file.
     *
     * @var  string
     */
    protected string $stubName = 'composer.stub';

    /**
     * Reads all data for the component to be generated (as well as the mappings for path, file and stubs)
     *
     * @return  array|null
     */
    public function getUserInputs(): ?array
    {
        $ui = Str::lower(
            $this->checkParameterOrChoice('ui', 'Select the UI for this container', ['API', 'WEB', 'BOTH'], 0)
        );

        // container name as inputted and lower
        $_sectionName = Str::lower($this->sectionName);

        // container name as inputted and lower
        $_containerName = Str::kebab($this->containerName);

        if ('api' === $ui || 'both' === $ui) {
            $this->call('nucleus:generate:container:api', [
                '--section' => $this->sectionName,
                '--container' => $this->containerName,
                '--file' => 'composer',
                '--maincalled' => true,
            ]);
        }

        if ('web' === $ui || 'both' === $ui) {
            $this->call('nucleus:generate:container:web', [
                '--section' => $this->sectionName,
                '--container' => $this->containerName,
                '--file' => 'composer',
                '--maincalled' => true,
            ]);
        }

        return [
            'path-parameters' => [
                'section-name' => $this->sectionName,
                'container-name' => $this->containerName,
            ],
            'stub-parameters' => [
                '_section-name' => $_sectionName,
                'section-name' => $this->sectionName,
                '_container-name' => $_containerName,
                'container-name' => $this->containerName,
                'class-name' => $this->fileName,
            ],
            'file-parameters' => [
                'file-name' => $this->fileName,
            ],
        ];
    }

    /**
     * Get the default file name for this component to be generated
     */
    public function getDefaultFileName(): string
    {
        return 'composer';
    }

    /**
     * @return string
     */
    public function getDefaultFileExtension(): string
    {
        return 'json';
    }
}
