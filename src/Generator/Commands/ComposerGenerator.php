<?php

declare(strict_types=1);

namespace Nucleus\Generator\Commands;

use Illuminate\Support\Str;
use Nucleus\Generator\GeneratorCommand;
use Nucleus\Generator\Interfaces\ComponentsGenerator;

class ComposerGenerator extends GeneratorCommand implements ComponentsGenerator
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
    protected $name = 'nucleus:generate:composer';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a composer file for a Container';
    /**
     * The type of class being generated.
     */
    protected string $fileType = 'composer';
    /**
     * The structure of the file path.
     */
    protected string $pathStructure = '{section-name}/{container-name}/*';
    /**
     * The structure of the file name.
     */
    protected string $nameStructure = '{file-name}';
    /**
     * The name of the stub file.
     */
    protected string $stubName = 'composer.stub';

    #[\Override] public function getUserInputs(): ?array
    {
        $section_name = Str::kebab($this->sectionName);
        $container_name = Str::kebab($this->containerName);

        return [
            'path-parameters' => [
                'section-name' => $this->sectionName,
                'container-name' => $this->containerName,
            ],
            'stub-parameters' => [
                '_section-name' => $section_name,
                'section-name' => $this->sectionName,
                '_container-name' => $container_name,
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

    public function getDefaultFileExtension(): string
    {
        return 'json';
    }
}
