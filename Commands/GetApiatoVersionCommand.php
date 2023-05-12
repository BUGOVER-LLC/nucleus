<?php

namespace Nucleus\Commands;

use Nucleus\Abstracts\Commands\ConsoleCommand;
use Nucleus\Foundation\Nuclear;

class GetApiatoVersionCommand extends ConsoleCommand
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'apiato';

    /**
     * The console command description.
     */
    protected $description = 'Display the current Apiato version.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info(Nuclear::VERSION);
    }
}
