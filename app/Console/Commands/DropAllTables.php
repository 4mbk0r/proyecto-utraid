<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DropAllTables extends Command
{
    protected $signature = 'db:drop-all-tables';

    protected $description = 'Drops all tables in the database';

    public function handle()
    {
        $tables = DB::select("SELECT tablename FROM pg_tables WHERE schemaname = 'public'");
        $tables = array_column($tables, 'tablename');
        
        foreach ($tables as $table) {
            DB::statement("DROP TABLE IF EXISTS $table CASCADE;");
        }
        DB::statement('DROP FUNCTION IF EXISTS generate_auto_increment(prefix text)');
        
        $this->info('All tables dropped successfully.');
    }
}
