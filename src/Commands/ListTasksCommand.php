<?php

declare(strict_types=1);

namespace Nucleus\Commands;

use Illuminate\Support\Facades\File;
use Nucleus\Abstracts\Command\ConsoleCommand;
use Nucleus\Foundation\Facades\Nuclear;
use Symfony\Component\Console\Output\ConsoleOutput;

class ListTasksCommand extends ConsoleCommand
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'nucleus:list:tasks {--withfilename}';

    /**
     * The console command description.
     */
    protected $description = 'List all Tasks in the Application.';

    public function __construct(private readonly ConsoleOutput $console)
    {
        parent::__construct();
    }

    public function handle(): void
    {
        foreach (Nuclear::getSectionNames() as $section_name) {
            foreach (Nuclear::getSectionContainerNames($section_name) as $container_name) {
                $this->console->writeln("<fg=yellow> [$container_name]</fg=yellow>");

                $directory = base_path(
                    config('app.path') . 'Containers/' . $section_name . '/' . $container_name . '/Tasks'
                );

                if (File::isDirectory($directory)) {
                    $files = File::allFiles($directory);

                    foreach ($files as $action) {
                        // Get the file name as is
                        $file_name = $originalFileName = $action->getFilename();

                        // Remove the Task.php postfix from each file name
                        // Further, remove the `.php', if the file does not end on 'Task.php'
                        $file_name = str_replace(['Task.php', '.php'], '', $file_name);

                        // UnCamelize the word and replace it with spaces
                        $file_name = uncamelize($file_name);

                        // Check if flag exists
                        $includeFileName = '';
                        if ($this->option('withfilename')) {
                            $includeFileName = "<fg=red>($originalFileName)</fg=red>";
                        }

                        $this->console->writeln("<fg=green>  - $file_name</fg=green>  $includeFileName");
                    }
                }
            }
        }
    }
}
