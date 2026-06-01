<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Services\DuplicateDetectionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ScanForDuplicatePersons implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public float $threshold = 0.7, public int $limitPerPerson = 10)
    {
    }

    public function handle(DuplicateDetectionService $detector): void
    {
        // scan and persist DuplicateMatch records
        $detector->scan($this->threshold, $this->limitPerPerson);
    }
}
