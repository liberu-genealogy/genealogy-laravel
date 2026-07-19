<?php

declare(strict_types=1);

namespace App\Services\Dna;

/**
 * Estimates a genealogical relationship from a total shared-autosomal-cM value.
 *
 * Standalone replacement for the range logic in AdvancedDnaMatchingService::predictRelationship()
 * / calculateMatchQuality(). Cleaned into monotonic, non-overlapping cM bands (the original set
 * overlapped — e.g. First Cousin 425-850 sat inside Aunt/Uncle 680-1150 — and dropped everything
 * above 3700 to "no match"). Bands follow the standard shared-cM reference (Shared cM Project /
 * DNA Painter): a single lower-bound threshold per band, evaluated high-to-low.
 */
class RelationshipEstimator
{
    /**
     * Lower-bound (inclusive) total-cM thresholds, highest first.
     * [min cM, predicted_relationship, confidence_level]
     */
    private const BANDS = [
        // ~6800 cM = 100% shared = identical twin or the same person / kit re-tested.
        [4000.0, 'Identical Twin / Same Person', 'very_high'],
        // ~3400-3720 cM.
        [3400.0, 'Parent/Child', 'very_high'],
        // ~2300-2900 cM (full siblings can reach ~3400).
        [2300.0, 'Full Sibling', 'very_high'],
        // ~1300-2300 cM: grandparent/grandchild, aunt/uncle, niece/nephew, half-sibling.
        [1300.0, 'Grandparent/Aunt/Uncle/Half-sibling', 'high'],
        // ~575-1300 cM: 1st cousin, great-aunt/uncle, half-aunt/uncle, 1C once-removed (close).
        [575.0, '1st Cousin', 'high'],
        // ~200-575 cM: 1st cousin once removed / 2nd cousin.
        [200.0, '1st Cousin Once Removed / 2nd Cousin', 'medium'],
        // ~75-200 cM: 2nd to 3rd cousin.
        [75.0, '2nd-3rd Cousin', 'medium'],
        // ~20-75 cM: distant cousin, still a plausible match.
        [20.0, 'Distant Cousin', 'low'],
    ];

    /**
     * The label emitted when a real comparison found nothing significant.
     * Distinct from the matching service's "no comparison ran" labels.
     */
    public const NO_MATCH_LABEL = 'Unrelated / No significant match';

    // cM below this is treated as noise / no genealogically significant match.
    private const NO_MATCH_THRESHOLD = 20.0;

    // ~6800 cM ≈ a fully-identical genome; used to scale the 0-100 quality score.
    private const MAX_EXPECTED_CM = 6800.0;

    /**
     * @return array{predicted_relationship: string, confidence_level: string, match_quality_score: float}
     */
    /**
     * Every relationship label this estimator can emit. Consumers that need to
     * tell a stored row apart from one written by some other path — a prune, an
     * audit — must be able to ask rather than hardcode the list.
     *
     * @return list<string>
     */
    public static function labels(): array
    {
        return [self::NO_MATCH_LABEL, ...array_column(self::BANDS, 1)];
    }

    public function estimate(float $totalSharedCm): array
    {
        $cm = max(0.0, $totalSharedCm);

        $relationship = self::NO_MATCH_LABEL;
        $confidence = 'low';

        if ($cm >= self::NO_MATCH_THRESHOLD) {
            foreach (self::BANDS as [$min, $label, $level]) {
                if ($cm >= $min) {
                    $relationship = $label;
                    $confidence = $level;
                    break;
                }
            }
        }

        return [
            'predicted_relationship' => $relationship,
            'confidence_level' => $confidence,
            // Monotonic 0-100: total cM as a fraction of a fully-identical genome (~6800 cM).
            'match_quality_score' => round(min(100.0, $cm / self::MAX_EXPECTED_CM * 100.0), 2),
        ];
    }
}
