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
 * Triangulation filters candidates on a minimum shared cM threshold. An
 * unreadable kit reports 0.0 cM, which clears a threshold of 0 — and the
 * triangulation page's minimum cM field accepts 0. Without an explicit
 * comparison check, every unreadable kit becomes a "match" and is persisted.
 */
class DnaTriangulationNoComparisonTest extends TestCase
{
    use RefreshDatabase;

    public function test_unreadable_kits_are_not_matches_even_at_a_zero_threshold(): void
    {
        Storage::fake('private');

        $base = $this->makeKit('a', 'kit_a.txt');
        $this->makeKit('b', 'kit_b.txt');

        $results = app(DnaTriangulationService::class)
            ->triangulateOneAgainstMany($base->id, null, 0.0);

        $this->assertSame([], $results['matches']);
        $this->assertSame(0, $results['significant_matches']);
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
