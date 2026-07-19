<?php

namespace App\Console\Commands;

use App\Models\Dna;
use App\Models\DnaMatching;
use App\Services\AdvancedDnaMatchingService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessLargeScaleDnaCommand extends Command
{
    #[\Override]
    protected $signature = 'dna:process-large-scale
                            {--batch-size=10 : Kits between progress reports and GC sweeps}
                            {--memory-limit=512M : Memory limit for the process}
                            {--timeout=3600 : Timeout in seconds for each batch}';

    #[\Override]
    protected $description = 'Process large-scale DNA matching with optimized memory usage and batching';

    public function __construct(protected AdvancedDnaMatchingService $advancedDnaMatchingService)
    {
        parent::__construct();
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
            // Consent gate (SCOPE §20): only ever read kits whose owner agreed
            // to matching. This used to chunk the whole table, so it compared
            // the genetic data of people who had not consented — harmless only
            // by the accident that it discarded everything it computed.
            $totalKits = Dna::consented()->count();
            $this->info("Consented DNA kits to process: {$totalKits}");

            if ($totalKits === 0) {
                $this->warn('No consented DNA kits found to process.');

                return Command::SUCCESS;
            }

            // Every consented kit's metadata — ids and file names only, so this
            // is small even for large installations. The kit FILES are read and
            // parsed per comparison inside the matching service, so memory is
            // bounded by one pair at a time regardless of how many kits exist.
            //
            // That matters, because chunking used to bound the comparisons
            // rather than the memory: kits were only ever compared against
            // others in the same chunk of --batch-size. At the default of 10,
            // 1000 kits had 0.9% of their pairs examined (4,500 of 499,500)
            // while the command reported completion. Pairing now spans the
            // whole consented set; --batch-size only controls how often
            // progress is reported and garbage collected.
            $kits = Dna::consented()->get()->values();
            $kitCount = $kits->count();

            $processedCount = 0;
            $errorCount = 0;
            $storedCount = 0;
            $startTime = microtime(true);

            for ($i = 0; $i < $kitCount; $i++) {
                for ($j = $i + 1; $j < $kitCount; $j++) {
                    try {
                        $storedCount += $this->compareAndStore($kits[$i], $kits[$j]);
                    } catch (Exception $e) {
                        $this->error("Error comparing kits {$kits[$i]->id} and {$kits[$j]->id}: ".$e->getMessage());
                        Log::error('Large-scale DNA processing pair error: '.$e->getMessage());
                        $errorCount++;
                    }
                }

                $processedCount++;

                if ($processedCount % $batchSize === 0 || $processedCount === $kitCount) {
                    gc_collect_cycles();
                    $this->info("Progress: {$processedCount}/{$kitCount} kits, {$storedCount} match(es) stored");
                    $this->showMemoryUsage();
                }
            }

            $endTime = microtime(true);
            $duration = round($endTime - $startTime, 2);

            $this->info('Large-scale DNA processing completed!');
            $this->info("Kits compared: {$processedCount} (all pairs)");
            $this->info("Matches stored: {$storedCount}");
            $this->info("Errors encountered: {$errorCount}");
            $this->info("Total duration: {$duration} seconds");

            // Report failure when any batch failed, so automation can see it.
            return $errorCount > 0 ? Command::FAILURE : Command::SUCCESS;

        } catch (Exception $e) {
            $this->error('Large-scale DNA processing failed: '.$e->getMessage());
            Log::error('Large-scale DNA processing failed: '.$e->getMessage());

            return Command::FAILURE;
        }
    }

    /**
     * Compare one pair and persist the result if a comparison actually ran.
     *
     * @return int 1 if a match row was written, 0 otherwise
     */
    protected function compareAndStore(Dna $kit1, Dna $kit2): int
    {
        $match = $this->advancedDnaMatchingService->performAdvancedMatching(
            $kit1->variable_name,
            $kit1->file_name,
            $kit2->variable_name,
            $kit2->file_name,
        );

        // A kit that could not be read yields a zeroed result. Recording it
        // would claim a comparison that never happened.
        if (! ($match['comparison_performed'] ?? false)) {
            return 0;
        }

        // dna_matchings has no unique constraint, so an unconditional create()
        // would duplicate every row on a second run — and this is a command an
        // operator is expected to run repeatedly. Keyed on the two kit files
        // rather than just the two users, because a user may own several kits
        // and a user-level key collapses them onto one row.
        $this->storeDirection($kit1, $kit2, $match);

        // The queued matching job writes a reciprocal row so the other party
        // sees the match too. Do the same, or a cross-team match would be
        // recorded only in the first kit owner's tenant.
        if ($kit1->user_id !== $kit2->user_id) {
            $this->storeDirection($kit2, $kit1, $match);
        }

        return 1;
    }

    /**
     * @param  array<string, mixed>  $match
     */
    private function storeDirection(Dna $owner, Dna $other, array $match): void
    {
        DnaMatching::withoutGlobalScopes()->updateOrCreate(
            [
                'user_id' => $owner->user_id,
                'match_id' => $other->user_id,
                'file1' => $owner->file_name,
                'file2' => $other->file_name,
            ],
            [
                // Runs unauthenticated, so BelongsToTenant cannot assign the
                // team; key the row to this kit owner's team, null if they have
                // none (fail closed).
                'team_id' => $owner->user?->current_team_id,
                'match_name' => $other->user->name,
                'total_shared_cm' => $match['total_cms'],
                'largest_cm_segment' => $match['largest_cm'],
                'confidence_level' => $match['confidence_level'] ?? null,
                'predicted_relationship' => $match['predicted_relationship'] ?? null,
                'shared_segments_count' => $match['shared_segments_count'] ?? null,
                'match_quality_score' => $match['match_quality_score'] ?? null,
                'chromosome_breakdown' => $match['chromosome_breakdown'] ?? null,
                'analysis_date' => now(),
            ]
        );
    }

    /**
     * Show current memory usage
     */
    protected function showMemoryUsage(): void
    {
        $memoryUsage = memory_get_usage(true);
        $memoryPeak = memory_get_peak_usage(true);

        $this->info('Memory usage: '.$this->formatBytes($memoryUsage).
                   ' (Peak: '.$this->formatBytes($memoryPeak).')');
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

        $bytes /= 1024 ** $pow;

        return round($bytes, 2).' '.$units[$pow];
    }
}
