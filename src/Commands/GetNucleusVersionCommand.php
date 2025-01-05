<?php

declare(strict_types=1);

namespace Nucleus\Commands;

use Nucleus\Abstracts\Command\ConsoleCommand;
use Nucleus\Foundation\Nuclear;

class GetNucleusVersionCommand extends ConsoleCommand
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'nucleus';

    /**
     * The console command description.
     */
    protected $description = 'Display the current Nucleus version.';

    /**
     * @return void
     */
    public function handle(): void
    {
        $this->info(Nuclear::VERSION);
    }
}
