<?php

declare(strict_types=1);

namespace Nucleus\Generator\Commands;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Pluralizer;
use Illuminate\Support\Str;
use Nucleus\Generator\GeneratorCommand;
use Nucleus\Generator\Interfaces\ComponentsGenerator;
use Symfony\Component\Console\Input\InputOption;

class MigrationGenerator extends GeneratorCommand implements ComponentsGenerator
{
    /**
     * User required/optional inputs expected to be passed while calling the command.
     * This is a replacement of the `getArguments` function "which reads whenever it's called".
     *
     * @var  array
     */
    public array $inputs = [
        ['tablename', null, InputOption::VALUE_OPTIONAL, 'The name for the database table'],
        ['connectionname', null, InputOption::VALUE_OPTIONAL, 'The name for the database connection'],
    ];
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'nucleus:generate:migration';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an "empty" migration file for a Container';
    /**
     * The type of class being generated.
     */
    protected string $fileType = 'Migration';
    /**
     * The structure of the file path.
     */
    protected string $pathStructure = '{section-name}/{container-name}/Data/Migrations/*';
    /**
     * The structure of the file name.
     */
    protected string $nameStructure = '{date}_{file-name}';
    /**
     * The name of the stub file.
     */
    protected string $stubName = 'migrations/migration.stub';

    public function getUserInputs(): ?array
    {
        $table_name = Str::lower(
            $this->checkParameterOrAsk(
                'tablename',
                'Enter the name of the database table',
                Str::snake(Pluralizer::plural($this->containerName))
            )
        );

        $connection_name = $this->checkParameterOrAsk(
            'connectionname',
            'Enter the name of the database connection',
            config('database.default')
        );

        $exists = false;

        $folder = $this->parsePathStructure($this->pathStructure, [
            'section-name' => $this->sectionName,
            'container-name' => $this->containerName,
        ]);
        $folder = $this->getFilePath($folder);
        $folder = rtrim($folder, $this->parsedFileName . '.' . $this->getDefaultFileExtension());

        $migration_name = $this->fileName . '.' . $this->getDefaultFileExtension();

        // Get the content of this folder
        $files = File::allFiles($folder);
        foreach ($files as $file) {
            if (Str::endsWith($file->getFilename(), $migration_name)) {
                $exists = true;
            }
        }

        if ($exists) {
            return null;
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
                'class-name' => Str::studly($this->fileName),
                'table-name' => $table_name,
                'connection' => $connection_name,
            ],
            'file-parameters' => [
                'date' => Carbon::now()->format('Y_m_d_His'),
                'file-name' => $this->fileName,
            ],
        ];
    }

    /**
     * Get the default file name for this component to be generated
     */
    public function getDefaultFileName(): string
    {
        return 'create_' . Str::snake(Pluralizer::plural(Str::lower($this->containerName))) . '_table';
    }

    /**
     * Removes "special characters" from a string
     */
    protected function removeSpecialChars($str): string
    {
        return $str;
    }
}
