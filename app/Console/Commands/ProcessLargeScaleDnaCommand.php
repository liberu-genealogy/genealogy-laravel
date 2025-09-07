<?php

namespace App\Console\Commands;

use App\Models\Dna;
use App\Services\AdvancedDnaMatchingService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessLargeScaleDnaCommand extends Command
{
    protected $signature = 'dna:process-large-scale
                            {--batch-size=10 : Number of DNA kits to process in each batch}
                            {--memory-limit=512M : Memory limit for the process}
                            {--timeout=3600 : Timeout in seconds for each batch}';

    protected $description = 'Process large-scale DNA matching with optimized memory usage and batching';

    protected AdvancedDnaMatchingService $advancedDnaMatchingService;

    public function __construct(AdvancedDnaMatchingService $advancedDnaMatchingService)
    {
        parent::__construct();
        $this->advancedDnaMatchingService = $advancedDnaMatchingService;
    }

    public function handle(): int
    {
        $batchSize = (int) $this->option('batch-size');
        $memoryLimit = $this->option('memory-limit');
        $timeout = (int) $this->option('timeout');

        // Set memory limit
        ini_set('memory_limit', $memoryLimit);
        set_time_limit($timeout);

        $this->info("Starting large-scale DNA processing with batch size: {$batchSize}");
        $this->info("Memory limit: {$memoryLimit}, Timeout: {$timeout}s");

        try {
            // Get all DNA kits
            $totalKits = Dna::count();
            $this->info("Total DNA kits to process: {$totalKits}");

            if ($totalKits === 0) {
                $this->warn('No DNA kits found to process.');
                return Command::SUCCESS;
            }

            // Process in chunks to manage memory
            $processedCount = 0;
            $errorCount = 0;
            $startTime = microtime(true);

            Dna::chunk($batchSize, function ($dnaKits) use (&$processedCount, &$errorCount, $batchSize) {
                $this->info("Processing batch of {$dnaKits->count()} DNA kits...");

                try {
                    // Convert to array format expected by the service
                    $kitsArray = $dnaKits->map(function ($kit) {
                        return [
                            'id' => $kit->id,
                            'variable_name' => $kit->variable_name,
                            'file_name' => $kit->file_name,
                            'user_id' => $kit->user_id
                        ];
                    })->toArray();

                    // Process the batch
                    $results = $this->advancedDnaMatchingService->processLargeScaleMatching($kitsArray);

                    $this->info("Successfully processed batch. Generated " . count($results) . " match results.");
                    $processedCount += $dnaKits->count();

                    // Store results in database
                    $this->storeMatchResults($results);

                } catch (\Exception $e) {
                    $this->error("Error processing batch: " . $e->getMessage());
                    Log::error('Large-scale DNA processing batch error: ' . $e->getMessage());
                    $errorCount++;
                }

                // Force garbage collection between batches
                gc_collect_cycles();

                // Show progress
                $this->info("Progress: {$processedCount} kits processed");
                $this->showMemoryUsage();
            });

            $endTime = microtime(true);
            $duration = round($endTime - $startTime, 2);

            $this->info("Large-scale DNA processing completed!");
            $this->info("Total kits processed: {$processedCount}");
            $this->info("Errors encountered: {$errorCount}");
            $this->info("Total duration: {$duration} seconds");

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Large-scale DNA processing failed: ' . $e->getMessage());
            Log::error('Large-scale DNA processing failed: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Store match results in the database
     */
    protected function storeMatchResults(array $results): void
    {
        foreach ($results as $result) {
            try {
                // Here you would typically queue individual jobs to store results
                // to avoid blocking the main process
                \App\Jobs\DnaMatching::dispatch(
                    \App\Models\User::find($result['kit1_id']),
                    "kit_{$result['kit1_id']}",
                    "file_{$result['kit1_id']}"
                );

            } catch (\Exception $e) {
                Log::error("Failed to queue DNA matching job: " . $e->getMessage());
            }
        }
    }

    /**
     * Show current memory usage
     */
    protected function showMemoryUsage(): void
    {
        $memoryUsage = memory_get_usage(true);
        $memoryPeak = memory_get_peak_usage(true);

        $this->info("Memory usage: " . $this->formatBytes($memoryUsage) .
                   " (Peak: " . $this->formatBytes($memoryPeak) . ")");
    }

    /**
     * Format bytes to human readable format
     */
    protected function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
