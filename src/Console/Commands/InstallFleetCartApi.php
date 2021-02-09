<?php

namespace Arif\FleetCartApi\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class InstallFleetCartApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fleetcart-api:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will setup passport and necessary things for this package.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Artisan::call('migrate');
        Artisan::call('passport:install');
    }
}
