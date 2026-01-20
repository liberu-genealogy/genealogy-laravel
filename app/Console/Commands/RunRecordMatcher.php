<?php

namespace App\Console\Commands;

use App\Jobs\RunRecordMatchingJob;
use Illuminate\Console\Command;

class RunRecordMatcher extends Command
{
    protected $signature = 'ai:run-matcher {--queue=default}';
    protected $description = 'Run the AI record matching job (dispatch to queue).';

    public function handle()
    {
        RunRecordMatchingJob::dispatch()->onQueue($this->option('queue'));
        $this->info('Record matching job dispatched.');
    }
}
