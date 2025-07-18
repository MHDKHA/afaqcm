<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateAfaqcmMigration extends Command
{
    protected $signature = 'make:afaqcm-migration';
    protected $description = 'Generate unified Afaqcm database migration';

    public function handle(): int
    {
        $directory = database_path('migrations/Afaqcm');

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filePath = $directory.'/Afaqcm_database.php';

        $stub = File::get(__DIR__.'/stubs/afaqcm_migration.stub');

        File::put($filePath, $stub);

        $this->info('Migration generated: '.$filePath);

        return Command::SUCCESS;
    }
}
