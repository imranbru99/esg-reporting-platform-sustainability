<?php

namespace App\Console\Commands;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DemoResetCommand extends Command
{
    protected $signature = 'demo:reset';

    protected $description = 'Wipe and re-seed clean realistic demo data and 22 user roles in under 30 seconds';

    public function handle(): int
    {
        $this->info('🌿 Resetting ESG Reporting Platform Demo Environment...');

        $startTime = microtime(true);

        if (DB::transactionLevel() > 0 || app()->environment('testing')) {
            $this->call('migrate', [
                '--force' => true,
            ]);
        } else {
            $this->call('migrate:fresh', [
                '--force' => true,
            ]);
        }

        $this->call('db:seed', [
            '--class' => DatabaseSeeder::class,
            '--force' => true,
        ]);

        $duration = round(microtime(true) - $startTime, 2);

        $this->newLine();
        $this->info("✅ Demo environment reset successfully in {$duration} seconds!");
        $this->line('   - 3 Demo Enterprises seeded (Apex Textile, Bengal Bank, CloudScale)');
        $this->line('   - 75 Modules, 9 Global Frameworks, and 12 Industry Presets loaded');
        $this->line("   - 22 Demo User Roles created with password 'password'");
        $this->line('   - Explore at: http://localhost:8000/demo-center or /admin');

        return self::SUCCESS;
    }
}
