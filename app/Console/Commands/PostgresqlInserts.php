<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class PostgresqlInserts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:postgresql-inserts';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a backup of the PostgreSQL database with only INSERT statements.';
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
     * @return int
     */
    public function handle()
    {
      
        Artisan::call('backup:run --only-db --only-to-disk=local --database=utraid --extra-mysqldump-options="--skip-extended-insert --compact --no-create-info"');

    }
}
