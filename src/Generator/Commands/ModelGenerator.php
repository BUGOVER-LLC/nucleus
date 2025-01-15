<?php

declare(strict_types=1);

namespace Nucleus\Generator\Commands;

use Illuminate\Support\Str;
use Nucleus\Generator\GeneratorCommand;
use Nucleus\Generator\Interfaces\ComponentsGenerator;
use Symfony\Component\Console\Input\InputOption;

class ModelGenerator extends GeneratorCommand implements ComponentsGenerator
{
    /**
     * User required/optional inputs expected to be passed while calling the command.
     * This is a replacement of the `getArguments` function "which reads whenever it's called".
     *
     * @var  array
     */
    public array $inputs = [
        ['repository', null, InputOption::VALUE_OPTIONAL, 'Generate the corresponding Repository for this Model?'],
    ];
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'nucleus:generate:model';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Model class';
    /**
     * The type of class being generated.
     */
    protected string $fileType = 'Model';
    /**
     * The structure of the file path.
     */
    protected string $pathStructure = '{section-name}/{container-name}/Domain/Model/*';
    /**
     * The structure of the file name.
     */
    protected string $nameStructure = '{file-name}';
    /**
     * The name of the stub file.
     */
    protected string $stubName = 'model.stub';

    public function getUserInputs(): ?array
    {
        $repository = $this->checkParameterOrConfirm(
            'repository',
            'Do you want to generate the corresponding Repository for this Model?',
            true
        );

        if ($repository) {
            // We need to generate a corresponding repository
            // so call the other command
            $status = $this->call('nucleus:generate:repository', [
                '--section' => $this->sectionName,
                '--container' => $this->containerName,
                '--file' => $this->fileName . 'Repository',
            ]);

            if (0 !== $status) {
                $this->printErrorMessage("Couldn't generate the corresponding Repository!");
            }

            $this->call('nucleus:generate:seeder', [
                '--section' => $this->sectionName,
                '--container' => $this->containerName,
                '--file' => $this->fileName . 'Seeder',
            ]);
        }

        return [
            'path-parameters' => [
                'section-name' => $this->sectionName,
                'container-name' => $this->containerName,
            ],
            'stub-parameters' => [
                '_section-name' => Str::lower($this->sectionName),
                'section-name' => $this->sectionName,
                '_container-name' => Str::lower($this->containerName),
                'container-name' => $this->containerName,
                'class-name' => $this->fileName,
                'resource-key' => $this->fileName,
            ],
            'file-parameters' => [
                'file-name' => $this->fileName,
            ],
        ];
    }
}
