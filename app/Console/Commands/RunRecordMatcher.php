<?php

namespace App\Console\Commands;

use App\Jobs\RunRecordMatchingJob;
use Illuminate\Console\Command;

/**
 * Runs record matching across every team — global by design.
 *
 * It dispatches RunRecordMatchingJob, which reads people from all teams and
 * writes each suggestion stamped with the team of the person it is about (see
 * the job). This command establishes no tenant of its own, and should not: it
 * is the scheduled entry point for the whole application, not one team's action.
 */
class RunRecordMatcher extends Command
{
    #[\Override]
    protected $signature = 'ai:run-matcher {--queue=default}';

    #[\Override]
    protected $description = 'Run the AI record matching job (dispatch to queue).';

    public function handle(): void
    {
        RunRecordMatchingJob::dispatch()->onQueue($this->option('queue'));
        $this->info('Record matching job dispatched.');
    }
}
