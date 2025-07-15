<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\AfaqcmInitialSeeder;

class AfaqcmSetup extends Command
{
    protected $signature = 'afaqcm:setup';
    protected $description = 'Seed initial Afaqcm data and create super admin user';

    public function handle(): int
    {
        (new AfaqcmInitialSeeder())->run();
        $this->info('Afaqcm seed completed.');
        return self::SUCCESS;
    }
}
