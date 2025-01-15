<?php

declare(strict_types=1);

namespace Nucleus\Generator\Commands;

use Illuminate\Support\Pluralizer;
use Illuminate\Support\Str;
use Nucleus\Generator\GeneratorCommand;
use Nucleus\Generator\Interfaces\ComponentsGenerator;

class ValueGenerator extends GeneratorCommand implements ComponentsGenerator
{
    /**
     * User required/optional inputs expected to be passed while calling the command.
     * This is a replacement of the `getArguments` function "which reads whenever it's called".
     *
     * @var  array
     */
    public array $inputs = [];
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'nucleus:generate:value';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Value class';
    /**
     * The type of class being generated.
     */
    protected string $fileType = 'Value';
    /**
     * The structure of the file path.
     */
    protected string $pathStructure = '{section-name}/{container-name}/Domain/Value/*';
    /**
     * The structure of the file name.
     */
    protected string $nameStructure = '{file-name}';
    /**
     * The name of the stub file.
     */
    protected string $stubName = 'value.stub';

    public function getUserInputs(): ?array
    {
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
                'resource-key' => strtolower(Pluralizer::plural($this->fileName)),
            ],
            'file-parameters' => [
                'file-name' => $this->fileName,
            ],
        ];
    }

    public function getDefaultFileName(): string
    {
        return 'DefaultValue';
    }
}
