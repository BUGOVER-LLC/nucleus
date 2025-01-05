<?php

declare(strict_types=1);

namespace Nucleus\Commands;

use Illuminate\Support\Facades\Config;
use Nucleus\Abstracts\Command\ConsoleCommand;

class SeedTestingDataCommand extends ConsoleCommand
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'nucleus:seed-test';

    /**
     * The console command description.
     */
    protected $description = 'Seed testing data.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $this->call('db:seed', [
            '--class' => Config::get('nucleus.seeders.testing'),
        ]);

        $this->info('Testing Data Seeded Successfully.');
    }
}
