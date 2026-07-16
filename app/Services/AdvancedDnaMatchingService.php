<?php

namespace App\Services;

use App\Services\Dna\DnaFileVault;
use App\Services\Dna\RawDnaParser;
use App\Services\Dna\RelationshipEstimator;
use App\Services\Dna\SegmentMatcher;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Autosomal DNA matching between two kits.
 *
 * Rebuilt (C7) to run a real pipeline — RawDnaParser reads the uploaded raw
 * file, SegmentMatcher finds half-identical (IBD) segments, RelationshipEstimator
 * maps total shared cM to a relationship — rather than the previous placeholder
 * that referenced non-existent php-dna classes (the vendor php-dna package is a
 * non-functional half-port and is deliberately not used). If a kit file can't be
 * read/parsed the run degrades to performBasicMatching() (no fabricated result).
 */
class AdvancedDnaMatchingService
{
    public function __construct(
        private RawDnaParser $parser = new RawDnaParser(),
        private SegmentMatcher $matcher = new SegmentMatcher(),
        private RelationshipEstimator $estimator = new RelationshipEstimator(),
        private DnaFileVault $vault = new DnaFileVault(),
    ) {}

    /**
     * Match two kits identified by their stored file names (on the private disk).
     *
     * @return array<string, mixed>
     */
    public function performAdvancedMatching(string $varName1, string $fileName1, string $varName2, string $fileName2): array
    {
        try {
            $kit1 = $this->loadKit($fileName1);
            $kit2 = $this->loadKit($fileName2);

            if ($kit1 === [] || $kit2 === []) {
                throw new \RuntimeException('DNA kit data unavailable for matching');
            }

            $match        = $this->matcher->match($kit1, $kit2);
            $relationship = $this->estimator->estimate($match['total_shared_cm']);

            return [
                'total_cms'              => $match['total_shared_cm'],
                'largest_cm'             => $match['largest_cm_segment'],
                // The dna_matchings.confidence_level column is a double, so map the
                // estimator's categorical confidence to a numeric score here; the
                // categorical label is preserved in detailed_report.
                'confidence_level'       => $this->confidenceScore($relationship['confidence_level']),
                'predicted_relationship' => $relationship['predicted_relationship'],
                'shared_segments_count'  => $match['shared_segments_count'],
                'match_quality_score'    => $relationship['match_quality_score'],
                'detailed_report'        => $this->detailedReport($match, $relationship),
                'chromosome_breakdown'   => $this->chromosomeBreakdown($match['shared_segments']),
                'ibd_segments'           => $match['shared_segments'],
            ];
        } catch (\Throwable $e) {
            // Throwable, not Exception: keep any lower-level parse/IO error from
            // aborting the job — degrade to the basic (no-match) fallback.
            Log::error('Advanced DNA matching failed: ' . $e->getMessage());

            return $this->performBasicMatching();
        }
    }

    /**
     * Read + parse a kit file into the normalized chrom => (pos => genotype) map.
     *
     * @return array<string, array<int, string>>
     */
    protected function loadKit(string $fileName): array
    {
        if (! Storage::disk('private')->exists($fileName)) {
            Log::warning("DNA file not found: {$fileName}");

            return [];
        }

        // DNA files are encrypted at rest (SCOPE §20); read + decrypt in memory
        // and parse the content — never write plaintext back to disk. The vault
        // transparently returns legacy plaintext files unchanged.
        $content = $this->vault->read($fileName);

        return $content === '' ? [] : $this->parser->parseContent($content);
    }

    /**
     * Group the reported segments into a per-chromosome summary.
     *
     * @param  list<array{chromosome:string,start:int,end:int,cm:float,snps:int}>  $segments
     * @return array<string, array{segment_count:int,total_cm:float,largest_segment:float}>
     */
    protected function chromosomeBreakdown(array $segments): array
    {
        $breakdown = [];

        foreach ($segments as $seg) {
            $chr = $seg['chromosome'];
            $breakdown[$chr] ??= ['segment_count' => 0, 'total_cm' => 0.0, 'largest_segment' => 0.0];
            $breakdown[$chr]['segment_count']++;
            $breakdown[$chr]['total_cm']        = round($breakdown[$chr]['total_cm'] + $seg['cm'], 2);
            $breakdown[$chr]['largest_segment'] = max($breakdown[$chr]['largest_segment'], $seg['cm']);
        }

        return $breakdown;
    }

    /**
     * @param  array<string, mixed>  $match
     * @param  array<string, mixed>  $relationship
     * @return array<string, mixed>
     */
    protected function detailedReport(array $match, array $relationship): array
    {
        return [
            'analysis_date'          => now()->toISOString(),
            'total_shared_cm'        => $match['total_shared_cm'],
            'largest_segment_cm'     => $match['largest_cm_segment'],
            'shared_segments'        => $match['shared_segments_count'],
            'total_matching_snps'    => $match['total_matching_snps'],
            'predicted_relationship' => $relationship['predicted_relationship'],
            'confidence_level'       => $relationship['confidence_level'],
            'match_quality_score'    => $relationship['match_quality_score'],
            'analysis_notes'         => [
                'cM is estimated from physical distance (~1 cM/Mb); a real genetic '
                . 'recombination map is the upgrade path (see SegmentMatcher).',
            ],
        ];
    }

    /**
     * Map the estimator's categorical confidence to a 0-100 numeric score
     * (the confidence_level DB column is numeric).
     */
    protected function confidenceScore(string $confidence): float
    {
        return match ($confidence) {
            'very_high' => 95.0,
            'high'      => 80.0,
            'medium'    => 60.0,
            default     => 30.0,
        };
    }

    /**
     * Fail-safe result when a kit file can't be read/parsed. Reports no match
     * rather than fabricating one; the UI shows a "basic analysis" note.
     *
     * @return array<string, mixed>
     */
    protected function performBasicMatching(): array
    {
        return [
            'total_cms'              => 0.0,
            'largest_cm'             => 0.0,
            'confidence_level'       => 0.0,
            'predicted_relationship' => 'Unknown (Basic Analysis)',
            'shared_segments_count'  => 0,
            'match_quality_score'    => 0.0,
            'detailed_report'        => [
                'analysis_date'  => now()->toISOString(),
                'analysis_notes' => ['Basic analysis: DNA kit data could not be read for segment matching.'],
            ],
            'chromosome_breakdown'   => [],
            'ibd_segments'           => [],
        ];
    }

    /**
     * Process large-scale DNA data in batches (all-pairs within each batch).
     *
     * @param  list<array{id:int,variable_name:string,file_name:string}>  $dnaKits
     * @return list<array<string, mixed>>
     */
    public function processLargeScaleMatching(array $dnaKits): array
    {
        $results = [];

        foreach (array_chunk($dnaKits, 10) as $batch) {
            $results = array_merge($results, $this->processBatch($batch));
            gc_collect_cycles();
        }

        return $results;
    }

    /**
     * @param  list<array{id:int,variable_name:string,file_name:string}>  $batch
     * @return list<array<string, mixed>>
     */
    protected function processBatch(array $batch): array
    {
        $results = [];
        $count   = count($batch);

        for ($i = 0; $i < $count; $i++) {
            for ($j = $i + 1; $j < $count; $j++) {
                $results[] = [
                    'kit1_id'       => $batch[$i]['id'],
                    'kit2_id'       => $batch[$j]['id'],
                    'match_result'  => $this->performAdvancedMatching(
                        $batch[$i]['variable_name'],
                        $batch[$i]['file_name'],
                        $batch[$j]['variable_name'],
                        $batch[$j]['file_name'],
                    ),
                ];
            }
        }

        return $results;
    }
}
