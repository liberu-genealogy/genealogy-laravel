<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SetupGamificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gamification:setup {--fresh : Run fresh migrations}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set up the gamification system by running migrations and seeding achievements';

    /**
     * Execute the console command.
     */
    public function handle()
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