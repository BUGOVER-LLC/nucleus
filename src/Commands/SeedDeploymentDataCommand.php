<?php

declare(strict_types=1);

namespace Nucleus\Commands;

use Illuminate\Support\Facades\Config;
use Nucleus\Abstracts\Command\ConsoleCommand;

class SeedDeploymentDataCommand extends ConsoleCommand
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'nucleus:seed-deploy';

    /**
     * The console command description.
     */
    protected $description = 'Seed data for initial deployment.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $this->call('db:seed', [
            '--class' => Config::get('nucleus.seeders.deployment'),
        ]);

        $this->info('Deployment Data Seeded Successfully.');
    }
}
