<?php

declare(strict_types=1);

namespace Tests\Feature\Services\Dna;

use App\Models\Dna;
use App\Models\User;
use App\Services\AdvancedDnaMatchingService;
use App\Services\Dna\SegmentMatcher;
use App\Services\DnaTriangulationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * When a kit cannot be read, the matching service reported a confidence level
 * of 0.0 and a match quality score of 0.0. Those read as measurements, and no
 * measurement was taken.
 *
 * Every consumer today checks comparison_performed first, so no user currently
 * sees these — that was fixed by the guards added alongside the matching job.
 * This closes the hole underneath those guards: a future consumer that forgets
 * the check should get an obviously absent value rather than a plausible zero
 * it can quietly render as "0.00 quality" or "0% confidence".
 *
 * Same reasoning applied to handwriting transcription: absent is not zero, and
 * a zero that was never measured is the more dangerous of the two because it
 * looks like a result.
 */
class UnmeasuredConfidenceTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_unreadable_kit_reports_no_confidence_and_no_quality_score(): void
    {
        Storage::fake('private');

        $result = (new AdvancedDnaMatchingService)
            ->performAdvancedMatching('a', 'missing_a.txt', 'b', 'missing_b.txt');

        $this->assertFalse($result['comparison_performed']);
        $this->assertNull($result['confidence_level']);
        $this->assertNull($result['match_quality_score']);
    }

    public function test_a_real_comparison_still_reports_both_figures(): void
    {
        Storage::fake('private');

        $kit = $this->buildKit();
        Storage::disk('private')->put('kit_a.txt', $kit);
        Storage::disk('private')->put('kit_b.txt', $kit);

        $result = (new AdvancedDnaMatchingService)
            ->performAdvancedMatching('a', 'kit_a.txt', 'b', 'kit_b.txt');

        $this->assertTrue($result['comparison_performed']);
        $this->assertNotNull($result['confidence_level']);
        $this->assertNotNull($result['match_quality_score']);
        $this->assertGreaterThan(0, $result['match_quality_score']);
    }

    /**
     * A genuinely measured zero must survive. Suppressing it on truthiness
     * would reintroduce the same conflation from the other direction — that
     * happened on the transcription page before it was corrected.
     */
    public function test_a_measured_zero_quality_score_is_reported_not_suppressed(): void
    {
        Storage::fake('private');

        // Two kits with no allele in common on any shared position: a real
        // comparison that finds nothing.
        Storage::disk('private')->put('kit_a.txt', $this->buildKit('AA'));
        Storage::disk('private')->put('kit_b.txt', $this->buildKit('GG'));

        $result = (new AdvancedDnaMatchingService)
            ->performAdvancedMatching('a', 'kit_a.txt', 'b', 'kit_b.txt');

        $this->assertTrue($result['comparison_performed']);
        // Pinned exactly, not merely non-null: asserting "not null" would pass
        // if the score came back as any number at all.
        $this->assertSame(0.0, $result['match_quality_score'], 'A measured zero must not become null.');
        $this->assertSame(0.0, $result['total_cms']);
    }

    /**
     * The triangulation summary counted the kits it was *given*, before any of
     * them were read, and the page labelled that "Total Compared". Ten kits of
     * which four were unreadable reported ten comparisons.
     *
     * A count of work attempted, presented as work done.
     */
    public function test_the_summary_counts_comparisons_that_ran_not_kits_attempted(): void
    {
        Storage::fake('private');

        $kit = $this->buildKit();
        Storage::disk('private')->put('kit_a.txt', $kit);
        Storage::disk('private')->put('kit_b.txt', $kit);

        $base = $this->kit('a', 'kit_a.txt');
        $this->kit('b', 'kit_b.txt');
        // Two records with no file behind them.
        $this->kit('c', 'kit_c.txt');
        $this->kit('d', 'kit_d.txt');

        $results = app(DnaTriangulationService::class)
            ->triangulateOneAgainstMany($base->id, null, SegmentMatcher::MIN_CM);

        $this->assertSame(3, $results['total_attempted'], 'Three kits were offered for comparison.');
        $this->assertSame(1, $results['total_compared'], 'Only one of them could actually be read.');
        $this->assertSame(2, $results['unreadable_kits'], 'The other two must be reported, not silently dropped.');
    }

    private function kit(string $varName, string $fileName): Dna
    {
        return Dna::create([
            'name' => $varName,
            'variable_name' => $varName,
            'file_name' => $fileName,
            'user_id' => User::factory()->withPersonalTeam()->create()->id,
            'consent_given' => true,
            'consent_given_at' => now(),
        ]);
    }

    private function buildKit(string $genotype = 'AG'): string
    {
        $lines = [
            '# This data file generated by 23andMe',
            "rsid\tchromosome\tposition\tgenotype",
        ];

        for ($i = 0; $i < 600; $i++) {
            $pos = 1_000_000 + $i * 30_000;
            $lines[] = "rs{$i}\t1\t{$pos}\t{$genotype}";
        }

        return implode("\n", $lines)."\n";
    }
}
