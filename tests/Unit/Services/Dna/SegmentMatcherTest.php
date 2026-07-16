<?php

namespace Tests\Unit\Services\Dna;

use App\Services\Dna\SegmentMatcher;
use PHPUnit\Framework\TestCase;

/**
 * Pure unit tests for the HIR/IBD segment matcher. No DB, no framework kernel.
 *
 * Kits are built in-code with deterministic loops so the SNP-count and cM
 * thresholds (SegmentMatcher::MIN_SNPS / ::MIN_CM) are cleared on purpose.
 */
class SegmentMatcherTest extends TestCase
{
    private const STEP = 20_000; // bp between SNPs: 500+ SNPs comfortably span > MIN_CM

    public function test_identical_kit_against_itself_reports_a_segment(): void
    {
        $kit = $this->halfIdenticalRun('1', 1_000_000, 600);

        $result = (new SegmentMatcher)->match($kit, $kit);

        $this->assertGreaterThan(0, $result['total_shared_cm']);
        $this->assertGreaterThanOrEqual(1, $result['shared_segments_count']);
        $this->assertGreaterThan(0, $result['total_matching_snps']);
    }

    public function test_fully_disjoint_kits_share_nothing(): void
    {
        // Same chromosome, non-overlapping positions => zero shared SNPs.
        $kitA = $this->halfIdenticalRun('1', 1_000_000, 600);
        $kitB = $this->halfIdenticalRun('1', 900_000_000, 600);

        $result = (new SegmentMatcher)->match($kitA, $kitB);

        $this->assertSame(0.0, $result['total_shared_cm']);
        $this->assertSame(0.0, $result['largest_cm_segment']);
        $this->assertSame(0, $result['shared_segments_count']);
        $this->assertSame(0, $result['total_matching_snps']);
        $this->assertSame([], $result['shared_segments']);
    }

    public function test_one_long_run_plus_scattered_mismatches_yields_one_segment(): void
    {
        $start = 5_000_000;
        $count = 600; // > MIN_SNPS

        // One long half-identical run on chr1: both kits share the 'A' allele.
        $kitA = [];
        $kitB = [];
        $pos = $start;
        for ($i = 0; $i < $count; $i++) {
            $kitA['1'][$pos] = 'AG';
            $kitB['1'][$pos] = 'AA';
            $pos += self::STEP;
        }

        // Immediately followed by a scattered non-matching region on the same
        // chromosome (kitA homozygous A, kitB homozygous G => zero shared allele).
        // These never form a segment and, after MISMATCH_TOLERANCE consecutive
        // mismatches, cleanly close the run above.
        for ($i = 0; $i < 50; $i++) {
            $kitA['1'][$pos] = 'AA';
            $kitB['1'][$pos] = 'GG';
            $pos += self::STEP;
        }

        $result = (new SegmentMatcher)->match($kitA, $kitB);

        $this->assertSame(1, $result['shared_segments_count']);
        $segment = $result['shared_segments'][0];
        $this->assertSame('1', $segment['chromosome']);
        $this->assertGreaterThan(0, $segment['cm']);
        $this->assertSame($count, $segment['snps']);
        $this->assertSame($start, $segment['start']);
    }

    /**
     * Build a chromosome map of `$count` half-identical SNPs (both kits 'AG')
     * starting at `$start`, spaced STEP apart.
     *
     * @return array<string, array<int, string>>
     */
    private function halfIdenticalRun(string $chr, int $start, int $count): array
    {
        $kit = [];
        $pos = $start;
        for ($i = 0; $i < $count; $i++) {
            $kit[$chr][$pos] = 'AG';
            $pos += self::STEP;
        }

        return $kit;
    }
}
