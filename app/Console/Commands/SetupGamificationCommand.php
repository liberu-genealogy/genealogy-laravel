<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

/**
 * Seeds gamification reference data (achievements, levels) — global, no tenant.
 *
 * It touches no team-scoped records, so it establishes no team. Documented here
 * so the absence is a decision, not an oversight.
 */
class SetupGamificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    #[\Override]
    protected $signature = 'gamification:setup {--fresh : Run fresh migrations}';

    /**
     * The console command description.
     *
     * @var string
     */
    #[\Override]
    protected $description = 'Set up the gamification system by running migrations and seeding achievements';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Setting up gamification system...');

        // Run migrations
        if ($this->option('fresh')) {
            $this->info('Running fresh migrations...');
            Artisan::call('migrate:fresh', ['--seed' => true]);
        } else {
            $this->info('Running migrations...');
            Artisan::call('migrate');
        }

        // Seed achievements
        $this->info('Seeding achievements...');
        Artisan::call('db:seed', ['--class' => 'AchievementSeeder']);

        $this->info('✅ Gamification system setup complete!');
        $this->line('');
        $this->line('Next steps:');
        $this->line('1. Add App\\Providers\\GamificationServiceProvider to config/app.php providers array');
        $this->line('2. Visit /gamification to view the dashboard');
        $this->line('3. Start adding people to your family tree to earn points!');

        return 0;
    }
}
