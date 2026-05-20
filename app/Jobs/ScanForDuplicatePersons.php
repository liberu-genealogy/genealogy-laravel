<?php

namespace App\Jobs;

use App\Services\DuplicateDetectionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ScanForDuplicatePersons implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public float $threshold;
    public int $limitPerPerson;

    public function __construct(float $threshold = 0.7, int $limitPerPerson = 10)
    {
        $this->threshold = $threshold;
        $this->limitPerPerson = $limitPerPerson;
    }

    public function handle(DuplicateDetectionService $detector)
    {
        // scan and persist DuplicateMatch records
        $detector->scan($this->threshold, $this->limitPerPerson);
    }
}
