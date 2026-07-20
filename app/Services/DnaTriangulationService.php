<?php

namespace App\Services;

use App\Models\Dna;
use App\Models\DnaMatching;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class DnaTriangulationService
{
    const MAX_CHROMOSOME_NUMBER = 23;

    public function __construct(protected AdvancedDnaMatchingService $matchingService) {}

    /**
     * Perform triangulation: match one kit against multiple kits
     *
     * @param  int  $baseKitId  The primary DNA kit to match against
     * @param  array|null  $compareKitIds  Optional array of kit IDs to compare. If null, matches against all kits
     * @param  float  $minSharedCm  Minimum shared cM threshold to consider a match
     * @return array Triangulation results
     */
    public function triangulateOneAgainstMany(int $baseKitId, ?array $compareKitIds = null, float $minSharedCm = 20.0): array
    {
        $baseKit = Dna::findOrFail($baseKitId);

        // Get kits to compare against
        $compareKits = $this->getCompareKits($baseKitId, $compareKitIds);

        $results = [
            'base_kit' => [
                'id' => $baseKit->id,
                'name' => $baseKit->name,
                'variable_name' => $baseKit->variable_name,
            ],
            'matches' => [],
            'triangulated_groups' => [],
            // total_compared used to be $compareKits->count() — the number of
            // kits offered, counted before any of them were read — and the page
            // labelled it "Total Compared". Ten kits of which four were
            // unreadable reported ten comparisons: work attempted presented as
            // work done. It now counts comparisons that actually ran, and the
            // kits that could not be read are reported rather than dropped.
            'total_attempted' => $compareKits->count(),
            'total_compared' => 0,
            'unreadable_kits' => 0,
            'significant_matches' => 0,
        ];

        // Match base kit against all compare kits
        foreach ($compareKits as $compareKit) {
            try {
                $matchResult = $this->matchingService->performAdvancedMatching(
                    $baseKit->variable_name,
                    $baseKit->file_name,
                    $compareKit->variable_name,
                    $compareKit->file_name
                );

                // A kit that could not be read reports 0.0 cM, which clears a
                // threshold of 0 — reachable from the UI, where the minimum cM
                // field allows 0. Require an actual comparison, not just a
                // number that happens to pass the filter.
                if (! ($matchResult['comparison_performed'] ?? false)) {
                    $results['unreadable_kits']++;

                    continue;
                }

                $results['total_compared']++;

                if ($matchResult['total_cms'] >= $minSharedCm) {
                    $results['matches'][] = [
                        'kit_id' => $compareKit->id,
                        'kit_name' => $compareKit->name,
                        'user_id' => $compareKit->user_id,
                        'total_cms' => $matchResult['total_cms'],
                        'largest_cm' => $matchResult['largest_cm'],
                        'confidence_level' => $matchResult['confidence_level'],
                        'predicted_relationship' => $matchResult['predicted_relationship'],
                        'shared_segments_count' => $matchResult['shared_segments_count'],
                        'match_quality_score' => $matchResult['match_quality_score'],
                        'chromosome_breakdown' => $matchResult['chromosome_breakdown'] ?? [],
                    ];
                    $results['significant_matches']++;
                }

            } catch (Exception $e) {
                Log::error("Triangulation match failed between kit {$baseKitId} and {$compareKit->id}: ".$e->getMessage());
            }
        }

        // Sort matches by shared cM
        usort($results['matches'], fn (array $a, array $b): int => $b['total_cms'] <=> $a['total_cms']);

        return $results;
    }

    /**
     * Perform three-way triangulation: find shared segments among three kits
     *
     * @param  int  $kit1Id  First DNA kit ID
     * @param  int  $kit2Id  Second DNA kit ID
     * @param  int  $kit3Id  Third DNA kit ID
     * @return array Three-way triangulation results
     */
    public function triangulateThreeWay(int $kit1Id, int $kit2Id, int $kit3Id): array
    {
        $kit1 = Dna::findOrFail($kit1Id);
        $kit2 = Dna::findOrFail($kit2Id);
        $kit3 = Dna::findOrFail($kit3Id);

        // Get matches between all three pairs
        $match12 = $this->matchingService->performAdvancedMatching(
            $kit1->variable_name,
            $kit1->file_name,
            $kit2->variable_name,
            $kit2->file_name
        );

        $match13 = $this->matchingService->performAdvancedMatching(
            $kit1->variable_name,
            $kit1->file_name,
            $kit3->variable_name,
            $kit3->file_name
        );

        $match23 = $this->matchingService->performAdvancedMatching(
            $kit2->variable_name,
            $kit2->file_name,
            $kit3->variable_name,
            $kit3->file_name
        );

        // Find chromosomes where all three share DNA
        $triangulatedChromosomes = $this->findTriangulatedChromosomes(
            $match12['chromosome_breakdown'] ?? [],
            $match13['chromosome_breakdown'] ?? [],
            $match23['chromosome_breakdown'] ?? []
        );

        return [
            'kits' => [
                ['id' => $kit1->id, 'name' => $kit1->name],
                ['id' => $kit2->id, 'name' => $kit2->name],
                ['id' => $kit3->id, 'name' => $kit3->name],
            ],
            // True only when all three pairings were actually compared. A kit
            // that could not be read yields 0.0 cM, which is indistinguishable
            // from a real "shared nothing" result unless the caller is told.
            'comparison_performed' => ($match12['comparison_performed'] ?? false)
                && ($match13['comparison_performed'] ?? false)
                && ($match23['comparison_performed'] ?? false),
            'pairwise_matches' => [
                'kit1_kit2' => [
                    'comparison_performed' => $match12['comparison_performed'] ?? false,
                    'total_cms' => $match12['total_cms'],
                    'relationship' => $match12['predicted_relationship'],
                ],
                'kit1_kit3' => [
                    'comparison_performed' => $match13['comparison_performed'] ?? false,
                    'total_cms' => $match13['total_cms'],
                    'relationship' => $match13['predicted_relationship'],
                ],
                'kit2_kit3' => [
                    'comparison_performed' => $match23['comparison_performed'] ?? false,
                    'total_cms' => $match23['total_cms'],
                    'relationship' => $match23['predicted_relationship'],
                ],
            ],
            'triangulated_chromosomes' => $triangulatedChromosomes,
            'triangulation_score' => $this->calculateTriangulationScore($triangulatedChromosomes),
        ];
    }

    /**
     * Find all triangulated groups for a set of kits
     * This identifies clusters where multiple kits share DNA segments
     *
     * @param  array  $kitIds  Array of DNA kit IDs
     * @param  float  $minSharedCm  Minimum shared cM threshold
     * @return array Triangulated groups
     */
    public function findTriangulatedGroups(array $kitIds, float $minSharedCm = 20.0): array
    {
        $groups = [];
        $kitCount = count($kitIds);

        // For each combination of 3 or more kits, check for shared segments
        for ($i = 0; $i < $kitCount - 2; $i++) {
            for ($j = $i + 1; $j < $kitCount - 1; $j++) {
                for ($k = $j + 1; $k < $kitCount; $k++) {
                    try {
                        $result = $this->triangulateThreeWay(
                            $kitIds[$i],
                            $kitIds[$j],
                            $kitIds[$k]
                        );

                        // A group whose kits were never compared scores 0.0,
                        // which clears a threshold of 0 — the same hole guarded
                        // against in triangulateOneAgainstMany above.
                        if ($result['comparison_performed'] && $result['triangulation_score'] >= $minSharedCm) {
                            $groups[] = [
                                'kit_ids' => [$kitIds[$i], $kitIds[$j], $kitIds[$k]],
                                'triangulation_score' => $result['triangulation_score'],
                                'chromosomes' => count($result['triangulated_chromosomes']),
                            ];
                        }
                    } catch (Exception $e) {
                        Log::error("Failed to triangulate group [{$kitIds[$i]}, {$kitIds[$j]}, {$kitIds[$k]}]: ".$e->getMessage());
                    }
                }
            }
        }

        // Sort by triangulation score
        usort($groups, fn (array $a, array $b): int => $b['triangulation_score'] <=> $a['triangulation_score']);

        return $groups;
    }

    /**
     * Get kits to compare against
     *
     * @param  int  $baseKitId  Base kit ID to exclude
     * @param  array|null  $compareKitIds  Optional specific kit IDs
     */
    protected function getCompareKits(int $baseKitId, ?array $compareKitIds = null): Collection
    {
        $query = Dna::where('id', '!=', $baseKitId);

        if ($compareKitIds !== null) {
            $query->whereIn('id', $compareKitIds);
        }

        return $query->get();
    }

    /**
     * Find chromosomes where all three pairs share DNA
     *
     * @param  array  $breakdown12  Chromosome breakdown for kit 1-2
     * @param  array  $breakdown13  Chromosome breakdown for kit 1-3
     * @param  array  $breakdown23  Chromosome breakdown for kit 2-3
     * @return array Triangulated chromosomes
     */
    protected function findTriangulatedChromosomes(array $breakdown12, array $breakdown13, array $breakdown23): array
    {
        $triangulated = [];

        for ($chr = 1; $chr <= self::MAX_CHROMOSOME_NUMBER; $chr++) {
            $cm12 = $breakdown12[$chr]['total_cm'] ?? 0;
            $cm13 = $breakdown13[$chr]['total_cm'] ?? 0;
            $cm23 = $breakdown23[$chr]['total_cm'] ?? 0;

            // Only include if all three pairs share DNA on this chromosome
            if ($cm12 > 0 && $cm13 > 0 && $cm23 > 0) {
                $triangulated[$chr] = [
                    'chromosome' => $chr,
                    'kit1_kit2_cm' => round($cm12, 2),
                    'kit1_kit3_cm' => round($cm13, 2),
                    'kit2_kit3_cm' => round($cm23, 2),
                    'min_shared_cm' => round(min($cm12, $cm13, $cm23), 2),
                    'avg_shared_cm' => round(($cm12 + $cm13 + $cm23) / 3, 2),
                ];
            }
        }

        return $triangulated;
    }

    /**
     * Calculate overall triangulation score
     *
     * @param  array  $triangulatedChromosomes  Triangulated chromosomes
     * @return float Triangulation score
     */
    protected function calculateTriangulationScore(array $triangulatedChromosomes): float
    {
        if ($triangulatedChromosomes === []) {
            return 0.0;
        }

        // Sum of minimum shared cM across all triangulated chromosomes
        $totalMinCm = array_sum(array_column($triangulatedChromosomes, 'min_shared_cm'));

        return round($totalMinCm, 2);
    }

    /**
     * Store triangulation results in database
     *
     * @param  array  $results  Triangulation results
     * @param  string  $type  Type of triangulation (one_to_many, three_way, groups)
     */
    public function storeTriangulationResults(array $results, string $type = 'one_to_many'): void
    {
        // For one-to-many triangulation, store each match
        if ($type === 'one_to_many' && isset($results['matches'])) {
            $baseKitId = $results['base_kit']['id'];
            $baseKit = Dna::find($baseKitId);

            if (! $baseKit) {
                Log::warning('Triangulation base kit not found; skipping result storage', [
                    'base_kit_id' => $baseKitId,
                ]);

                return;
            }

            foreach ($results['matches'] as $match) {
                // file1/file2 are NOT NULL, so a match whose kit row is gone can't
                // be stored — skip it rather than dereferencing a null Dna::find.
                $matchKit = Dna::find($match['kit_id']);
                if (! $matchKit) {
                    Log::warning('Triangulation match kit not found; skipping match', [
                        'kit_id' => $match['kit_id'] ?? null,
                    ]);

                    continue;
                }

                // Check if match already exists
                $existing = DnaMatching::where('user_id', $baseKit->user_id)
                    ->where('match_id', $match['user_id'])
                    ->first();

                if ($existing) {
                    // Update existing record
                    $existing->update([
                        'total_shared_cm' => $match['total_cms'],
                        'largest_cm_segment' => $match['largest_cm'],
                        'confidence_level' => $match['confidence_level'],
                        'predicted_relationship' => $match['predicted_relationship'],
                        'shared_segments_count' => $match['shared_segments_count'],
                        'match_quality_score' => $match['match_quality_score'],
                        'chromosome_breakdown' => $match['chromosome_breakdown'],
                        'analysis_date' => now(),
                    ]);
                } else {
                    // Create new record. Triangulation runs unauthenticated (queue/
                    // console), so BelongsToTenant can't auto-assign team_id; key the
                    // row to the base kit owner's current team (null = fail-closed).
                    DnaMatching::create([
                        'team_id' => $baseKit->user?->current_team_id,
                        'user_id' => $baseKit->user_id,
                        'match_id' => $match['user_id'],
                        'match_name' => $match['kit_name'],
                        'file1' => $baseKit->file_name,
                        'file2' => $matchKit->file_name,
                        'total_shared_cm' => $match['total_cms'],
                        'largest_cm_segment' => $match['largest_cm'],
                        'confidence_level' => $match['confidence_level'],
                        'predicted_relationship' => $match['predicted_relationship'],
                        'shared_segments_count' => $match['shared_segments_count'],
                        'match_quality_score' => $match['match_quality_score'],
                        'chromosome_breakdown' => $match['chromosome_breakdown'],
                        'analysis_date' => now(),
                    ]);
                }
            }
        }
    }
}
