<?php

declare(strict_types=1);

namespace Nucleus\Commands;

use Illuminate\Support\Facades\File;
use Nucleus\Abstracts\Command\ConsoleCommand;
use Nucleus\Foundation\Facades\Nuclear;
use Symfony\Component\Console\Output\ConsoleOutput;

class ListActionsCommand extends ConsoleCommand
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'nucleus:list:actions {--withfilename}';

    /**
     * The console command description.
     */
    protected $description = 'List all Actions in the Application.';

    public function __construct(private readonly ConsoleOutput $console)
    {
        parent::__construct();
    }

    public function handle(): void
    {
        foreach (Nuclear::getSectionNames() as $section_name) {
            foreach (Nuclear::getSectionContainerNames($section_name) as $containerName) {
                $this->console->writeln("<fg=yellow> [$containerName]</fg=yellow>");

                $directory = base_path(
                    config('app.path') . 'Containers/' . $section_name . '/' . $containerName . '/Actions'
                );

                if (File::isDirectory($directory)) {
                    $files = File::allFiles($directory);

                    foreach ($files as $action) {
                        // Get the file name as is
                        $fileName = $originalFileName = $action->getFilename();

                        // Remove the Action.php postfix from each file name
                        // Further, remove the `.php', if the file does not end on 'Action.php'
                        $fileName = str_replace(['Action.php', '.php'], '', $fileName);

                        // UnCamelize the word and replace it with spaces
                        $fileName = uncamelize($fileName);

                        // Check if flag exists
                        $include_file_name = '';
                        if ($this->option('withfilename')) {
                            $include_file_name = "<fg=red>($originalFileName)</fg=red>";
                        }

                        $this->console->writeln("<fg=green>  - $fileName</fg=green>  $include_file_name");
                    }
                }
            }
        }
    }
}
