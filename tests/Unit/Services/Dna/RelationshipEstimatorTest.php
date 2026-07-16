<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Dna;

use App\Services\Dna\RelationshipEstimator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Pure unit test — no DB, no Laravel app boot. Extends PHPUnit's TestCase directly.
 */
class RelationshipEstimatorTest extends TestCase
{
    private RelationshipEstimator $estimator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->estimator = new RelationshipEstimator();
    }

    #[DataProvider('relationshipBands')]
    public function test_it_maps_cm_to_relationship_and_confidence(float $cm, string $relationship, string $confidence): void
    {
        $result = $this->estimator->estimate($cm);

        $this->assertSame($relationship, $result['predicted_relationship']);
        $this->assertSame($confidence, $result['confidence_level']);
    }

    public static function relationshipBands(): array
    {
        return [
            'identical/self' => [6800.0, 'Identical Twin / Same Person', 'very_high'],
            'parent/child'   => [3500.0, 'Parent/Child', 'very_high'],
            'full sibling'   => [2600.0, 'Full Sibling', 'very_high'],
            'grandparent grp'=> [1800.0, 'Grandparent/Aunt/Uncle/Half-sibling', 'high'],
            '1st cousin'     => [850.0, '1st Cousin', 'high'],
            '1cr/2nd cousin' => [350.0, '1st Cousin Once Removed / 2nd Cousin', 'medium'],
            '2nd-3rd cousin' => [100.0, '2nd-3rd Cousin', 'medium'],
            'distant cousin' => [40.0, 'Distant Cousin', 'low'],
            'unrelated'      => [5.0, 'Unrelated / No significant match', 'low'],
        ];
    }

    public function test_result_matches_the_contract_shape(): void
    {
        $result = $this->estimator->estimate(850.0);

        $this->assertSame(
            ['predicted_relationship', 'confidence_level', 'match_quality_score'],
            array_keys($result)
        );
        $this->assertIsString($result['predicted_relationship']);
        $this->assertIsString($result['confidence_level']);
        $this->assertIsFloat($result['match_quality_score']);
    }

    public function test_quality_score_is_bounded_0_to_100(): void
    {
        foreach ([0.0, 5.0, 100.0, 850.0, 3500.0, 6800.0, 99999.0] as $cm) {
            $score = $this->estimator->estimate($cm)['match_quality_score'];
            $this->assertGreaterThanOrEqual(0.0, $score);
            $this->assertLessThanOrEqual(100.0, $score);
        }
    }

    public function test_quality_score_increases_with_cm(): void
    {
        $samples = [5.0, 100.0, 850.0, 2600.0, 3500.0];
        $previous = -1.0;

        foreach ($samples as $cm) {
            $score = $this->estimator->estimate($cm)['match_quality_score'];
            $this->assertGreaterThan($previous, $score, "score should rise at {$cm} cM");
            $previous = $score;
        }
    }

    public function test_negative_cm_is_treated_as_no_match(): void
    {
        $result = $this->estimator->estimate(-10.0);

        $this->assertSame('Unrelated / No significant match', $result['predicted_relationship']);
        $this->assertSame(0.0, $result['match_quality_score']);
    }
}
