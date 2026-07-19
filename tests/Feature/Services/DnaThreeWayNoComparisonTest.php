<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use App\Models\Dna;
use App\Models\User;
use App\Services\DnaTriangulationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Three-way triangulation must not report a pairing it could not compute.
 *
 * Each of the three pairwise comparisons can independently fail to run when a
 * kit file is unreadable. The zeroed result is otherwise indistinguishable in
 * the output table from a genuine "compared, shared nothing" pairing, and the
 * derived triangulation score of 0.0 clears a zero minimum-cM threshold, so
 * unreadable kits would surface as a triangulated group.
 */
class DnaThreeWayNoComparisonTest extends TestCase
{
    use RefreshDatabase;

    public function test_pairwise_results_report_whether_a_comparison_happened(): void
    {
        Storage::fake('private');

        $a = $this->makeKit('a', 'kit_a.txt');
        $b = $this->makeKit('b', 'kit_b.txt');
        $c = $this->makeKit('c', 'kit_c.txt');

        $result = app(DnaTriangulationService::class)
            ->triangulateThreeWay($a->id, $b->id, $c->id);

        $this->assertFalse($result['comparison_performed']);

        foreach (['kit1_kit2', 'kit1_kit3', 'kit2_kit3'] as $pair) {
            $this->assertFalse(
                $result['pairwise_matches'][$pair]['comparison_performed'],
                "Pair {$pair} reported a comparison that never ran."
            );
        }
    }

    public function test_unreadable_kits_do_not_form_a_group_at_a_zero_threshold(): void
    {
        Storage::fake('private');

        $a = $this->makeKit('a', 'kit_a.txt');
        $b = $this->makeKit('b', 'kit_b.txt');
        $c = $this->makeKit('c', 'kit_c.txt');

        $groups = app(DnaTriangulationService::class)
            ->findTriangulatedGroups([$a->id, $b->id, $c->id], 0.0);

        $this->assertSame([], $groups);
    }

    private function makeKit(string $varName, string $fileName): Dna
    {
        return Dna::create([
            'name' => $varName,
            'variable_name' => $varName,
            'file_name' => $fileName,
            'user_id' => User::factory()->create()->id,
            'consent_given' => true,
            'consent_given_at' => now(),
        ]);
    }
}
