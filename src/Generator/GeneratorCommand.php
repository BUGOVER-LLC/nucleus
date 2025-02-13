<?php

declare(strict_types=1);

namespace Nucleus\Generator;

use DateTime;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem as IlluminateFilesystem;
use Illuminate\Support\Str;
use Nucleus\Exceptions\GeneratorErrorException;
use Nucleus\Generator\Interfaces\ComponentsGenerator;
use Nucleus\Generator\Traits\FileSystemTrait;
use Nucleus\Generator\Traits\FormatterTrait;
use Nucleus\Generator\Traits\ParserTrait;
use Nucleus\Generator\Traits\PrinterTrait;
use Symfony\Component\Console\Input\InputOption;

use function array_key_exists;

abstract class GeneratorCommand extends Command
{
    use ParserTrait;
    use PrinterTrait;
    use FileSystemTrait;
    use FormatterTrait;

    /**
     * Root directory of all sections
     *
     * @var string
     */
    private const string DEFAULT_ROOT = 'Containers';

    /**
     * Relative path for the stubs (relative to this directory / file)
     *
     * @var string
     */
    private const string STUB_PATH = 'Stubs/*';

    /**
     * Relative path for the custom stubs (relative to the app/Ship directory!)
     */
    private const string CUSTOM_STUB_PATH = 'Generators/CustomStubs/*';

    /**
     * Default section name
     *
     * @var string
     */
    private const string DEFAULT_SECTION_NAME = 'AppSection';

    /**
     * @var string
     */
    protected string $filePath;

    /**
     * @var string the name of the section to generate the stubs
     */
    protected string $sectionName;

    /**
     * @var string the name of the container to generate the stubs
     */
    protected string $containerName;

    /**
     * @var string The name of the file to be created (entered by the user)
     */
    protected string $fileName;

    /**
     * @var
     */
    protected null|array|string $userData = [];

    /**
     * @var
     */
    protected string $parsedFileName = '';

    /**
     * @var
     */
    protected string|array $stubContent;

    /**
     * @var
     */
    protected string|array $renderedStubContent;

    /**
     * @var array|array[]
     */
    private array $defaultInputs = [
        ['section', null, InputOption::VALUE_OPTIONAL, 'The name of the section'],
        ['container', null, InputOption::VALUE_OPTIONAL, 'The name of the container'],
        ['file', null, InputOption::VALUE_OPTIONAL, 'The name of the file'],
    ];

    /**
     * Create a new console command instance.
     *
     * @return void
     */
    public function __construct(private readonly IlluminateFilesystem $fileSystem)
    {
        parent::__construct();
    }

    /**
     * @void
     *
     * @throws GeneratorErrorException|FileNotFoundException
     */
    public function handle()
    {
        $this->validateGenerator($this);

        $this->sectionName = ucfirst(
            $this->checkParameterOrAsk('section', 'Enter the name of the Section', self::DEFAULT_SECTION_NAME)
        );
        $this->containerName = ucfirst($this->checkParameterOrAsk('container', 'Enter the name of the Container'));
        $this->fileName = $this->checkParameterOrAsk(
            'file',
            'Enter the name of the ' . $this->fileType . ' file',
            $this->getDefaultFileName()
        );

        // Now fix the section, container and file name
        $this->sectionName = $this->removeSpecialChars($this->sectionName);
        $this->containerName = $this->removeSpecialChars($this->containerName);
        if ('Configuration' !== $this->fileType) {
            $this->fileName = $this->removeSpecialChars($this->fileName);
        }

        // And we are ready to start
        $this->printStartedMessage($this->sectionName . ':' . $this->containerName, $this->fileName);

        // Get user inputs
        $this->userData = $this->getUserInputs();

        if (null === $this->userData) {
            // The user skipped this step
            return;
        }
        $this->userData = $this->sanitizeUserData($this->userData);

        // Get the actual path of the output file as well as the correct filename
        $this->parsedFileName = $this->parseFileStructure($this->nameStructure, $this->userData['file-parameters']);
        $this->filePath = $this->getFilePath(
            $this->parsePathStructure($this->pathStructure, $this->userData['path-parameters'])
        );

        if (!$this->fileSystem->exists($this->filePath)) {
            // Prepare stub content
            $this->stubContent = $this->getStubContent();
            $this->renderedStubContent = $this->parseStubContent(
                $this->stubContent,
                $this->userData['stub-parameters']
            );

            $this->generateFile($this->filePath, $this->renderedStubContent);

            $this->printFinishedMessage($this->fileType);
        }

        $this->info((new DateTime())->format('Y-m-d H:i:s'));

        // Exit the command successfully
        return 0;
    }

    /**
     * @param $generator
     *
     * @throws GeneratorErrorException
     */
    private function validateGenerator($generator): void
    {
        if (!$generator instanceof ComponentsGenerator) {
            throw new GeneratorErrorException(
                'Your component maker command should implement ComponentsGenerator interface.'
            );
        }
    }

    /**
     * Checks if the param is set (via CLI), otherwise asks the user for a value
     *
     * @param $param
     * @param $question
     * @param string|int $default
     *
     * @return mixed
     */
    protected function checkParameterOrAsk($param, $question, string|int $default = ''): mixed
    {
        // Check if we already have a param set
        $value = $this->option($param);
        if (null === $value) {
            // There was no value provided via CLI, so ask the user…
            $value = $this->ask($question, $default);
        }

        return $value;
    }

    /**
     * Get the default file name for this component to be generated.
     */
    protected function getDefaultFileName(): string
    {
        return Str::ucfirst($this->fileType);
    }

    /**
     * Removes "special characters" from a string
     *
     * @param $str
     * @return string
     */
    protected function removeSpecialChars(array|string $str): string|array|null
    {
        // remove everything that is NOT a character or digit
        return preg_replace('/[^A-Za-z0-9]/', '', $str);
    }

    /**
     * Checks, if the data from the generator contains path, stub and file-parameters.
     * Adds empty arrays, if they are missing
     *
     * @param array|object $data
     * @return array|object
     */
    private function sanitizeUserData(array|object $data): array|object
    {
        if (!array_key_exists('path-parameters', $data)) {
            $data['path-parameters'] = [];
        }

        if (!array_key_exists('stub-parameters', $data)) {
            $data['stub-parameters'] = [];
        }

        if (!array_key_exists('file-parameters', $data)) {
            $data['file-parameters'] = [];
        }

        return $data;
    }

    /**
     * @param string $path
     * @return string
     */
    protected function getFilePath(string $path): string
    {
        $rootPath = self::getRootPath()
            ? DIRECTORY_SEPARATOR . str_replace(
                '\\',
                '/',
                self::getRootPath() . '/' . $path
            ) . '.' . $this->getDefaultFileExtension()
            : base_path() . '/' . str_replace(
                '\\',
                '/',
                self::DEFAULT_ROOT . '/' . $path
            ) . '.' . $this->getDefaultFileExtension();

        // Try to create directory
        $this->createDirectory($rootPath);

        // Return full path
        return $rootPath;
    }

    /**
     * @return string
     */
    private static function getRootPath(): string
    {
        return config('app.path') . self::DEFAULT_ROOT;
    }

    /**
     * Get the default file extension for the file to be created.
     */
    protected function getDefaultFileExtension(): string
    {
        return 'php';
    }

    /**
     * @return  string
     * @throws FileNotFoundException
     */
    protected function getStubContent(): string
    {
        // Check if there is a custom file that overrides the default stubs
        $path = app_path() . '/Ship/' . self::CUSTOM_STUB_PATH;
        $file = str_replace('*', $this->stubName, $path);

        // Check if the custom file exists
        if (!$this->fileSystem->exists($file)) {
            // It does not exist - so take the default file!
            $path = __DIR__ . '/' . self::STUB_PATH;
            $file = str_replace('*', $this->stubName, $path);
        }

        // Now load the stub
        return $this->fileSystem->get($file);
    }

    /**
     * Get all the console command arguments, from the components. The default arguments are prepended
     */
    protected function getOptions(): array
    {
        return array_merge($this->defaultInputs, $this->inputs);
    }

    /**
     * @param      $arg
     * @param bool $trim
     *
     * @return array|string|null
     */
    protected function getInput($arg, bool $trim = true): array|string|null
    {
        return $trim ? $this->trimString($this->argument($arg)) : $this->argument($arg);
    }

    /**
     * Checks if the param is set (via CLI), otherwise proposes choices to the user
     *
     * @param $param
     * @param $question
     * @param $choices
     * @param mixed $default
     *
     * @return bool|array|string|null
     */
    protected function checkParameterOrChoice(
        $param,
        $question,
        $choices,
        mixed $default = null
    ): bool|array|string|null
    {
        // Check if we already have a param set
        $value = $this->option($param);
        if (null === $value) {
            // There was no value provided via CLI, so ask the user…
            $value = $this->choice($question, $choices, $default);
        }

        return $value;
    }

    /**
     * @param      $param
     * @param      $question
     * @param bool $default
     *
     * @return string|array|bool|null
     */
    protected function checkParameterOrConfirm($param, $question, bool $default = false): string|array|bool|null
    {
        // Check if we already have a param set
        $value = $this->option($param);
        if (null === $value) {
            // There was no value provided via CLI, so ask the user...
            $value = $this->confirm($question, $default);
        }

        return $value;
    }
}
