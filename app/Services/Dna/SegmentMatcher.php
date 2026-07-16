<?php

namespace App\Services\Dna;

/**
 * Half-identical-region (HIR / IBD) segment matcher for autosomal DNA.
 *
 * Detects shared DNA segments between two genotyped kits from first principles
 * (the vendor php-dna package is non-functional and is NOT used here).
 *
 * Input for both kits is a *normalized kit map*:
 *
 *   array<string, array<int, string>>  =  chromosome => (position => genotype)
 *
 * where chromosome is '1'..'22' (or 'X'), position is an integer base-pair
 * coordinate, and genotype is a 2-char UPPERCASE string with sorted alleles
 * (e.g. 'AG', 'CC'). Producing that shape is the parser's job, not this class's.
 *
 * Algorithm (standard HIR / half-identical detection):
 *   - For each autosome present in BOTH kits, take the positions shared by both,
 *     sorted ascending.
 *   - At each shared position the two genotypes are "half-identical" when they
 *     share at least one allele; a "mismatch" is zero shared alleles.
 *   - Walk the sorted shared SNPs building runs of consecutive half-identical
 *     SNPs, tolerating short bursts of genotyping error: a run is only broken
 *     after MISMATCH_TOLERANCE *consecutive* mismatches.
 *   - A run is reported as a segment when it holds at least MIN_SNPS
 *     half-identical SNPs AND spans at least MIN_CM.
 *
 * Only autosomes ('1'..'22') are considered. The X chromosome is intentionally
 * excluded — its inheritance and half-identical semantics differ (males are
 * hemizygous), so a naive autosomal walk over X would over-report.
 */
class SegmentMatcher
{
    /**
     * Minimum half-identical SNPs for a run to count as a real segment.
     * 500 is a common floor for array-density (~600k-700k SNP) autosomal data;
     * runs shorter than this are dominated by identical-by-state noise.
     */
    public const MIN_SNPS = 500;

    /**
     * Consecutive mismatches that break a run. Up to (MISMATCH_TOLERANCE - 1)
     * isolated mismatches inside an otherwise half-identical stretch are treated
     * as genotyping error and do not end the segment.
     */
    public const MISMATCH_TOLERANCE = 3;

    /**
     * Centimorgans per megabase used to turn physical span into a cM estimate.
     * 1.0 cM/Mb is the genome-wide average recombination rate.
     */
    public const CM_PER_MB = 1.0;

    /**
     * Minimum segment length in centimorgans. ~7 cM is the widely used
     * genealogical floor below which shared segments are largely false / IBS.
     */
    public const MIN_CM = 7.0;

    /**
     * @param  array<string, array<int, string>>  $kitA
     * @param  array<string, array<int, string>>  $kitB
     * @return array{
     *   total_shared_cm: float,
     *   largest_cm_segment: float,
     *   shared_segments_count: int,
     *   total_matching_snps: int,
     *   shared_segments: array<int, array{chromosome: string, start: int, end: int, cm: float, snps: int}>
     * }
     */
    public function match(array $kitA, array $kitB): array
    {
        $segments = [];

        // Autosomes only. range(1,22) cast to string; X is deliberately skipped.
        foreach (range(1, 22) as $n) {
            $chr = (string) $n;

            if (! isset($kitA[$chr], $kitB[$chr])) {
                continue;
            }

            // Positions genotyped in BOTH kits, ascending.
            $shared = array_keys(array_intersect_key($kitA[$chr], $kitB[$chr]));
            sort($shared, SORT_NUMERIC);

            $runStart = null;   // position of first half-identical SNP in the open run
            $runEnd = null;     // position of last half-identical SNP in the open run
            $runSnps = 0;       // half-identical SNP count in the open run
            $consecutiveMismatch = 0;

            foreach ($shared as $pos) {
                if ($this->isHalfIdentical($kitA[$chr][$pos], $kitB[$chr][$pos])) {
                    if ($runStart === null) {
                        $runStart = $pos;
                    }
                    $runEnd = $pos;
                    $runSnps++;
                    $consecutiveMismatch = 0;
                } elseif ($runStart !== null) {
                    // Mismatch inside an open run: tolerate a short burst, break on the limit.
                    if (++$consecutiveMismatch >= self::MISMATCH_TOLERANCE) {
                        $segment = $this->buildSegment($chr, $runStart, $runEnd, $runSnps);
                        if ($segment !== null) {
                            $segments[] = $segment;
                        }
                        $runStart = $runEnd = null;
                        $runSnps = 0;
                        $consecutiveMismatch = 0;
                    }
                }
                // A mismatch with no open run just moves on (runs never start on a mismatch).
            }

            // Close any run still open at the end of the chromosome.
            $segment = $this->buildSegment($chr, $runStart, $runEnd, $runSnps);
            if ($segment !== null) {
                $segments[] = $segment;
            }
        }

        $totalCm = 0.0;
        $largestCm = 0.0;
        $totalSnps = 0;
        foreach ($segments as $segment) {
            $totalCm += $segment['cm'];
            $largestCm = max($largestCm, $segment['cm']);
            $totalSnps += $segment['snps'];
        }

        return [
            'total_shared_cm' => round($totalCm, 2),
            'largest_cm_segment' => round($largestCm, 2),
            'shared_segments_count' => count($segments),
            'total_matching_snps' => $totalSnps,
            'shared_segments' => $segments,
        ];
    }

    /**
     * Two genotypes are half-identical (IBD-compatible) when they share at
     * least one allele. Both are 2-char strings, so four char comparisons
     * cover the full intersection without allocating arrays.
     */
    private function isHalfIdentical(string $a, string $b): bool
    {
        return $a[0] === $b[0] || $a[0] === $b[1]
            || $a[1] === $b[0] || $a[1] === $b[1];
    }

    /**
     * Turn an open run into a reportable segment, or null if it misses the
     * SNP-count or cM thresholds.
     *
     * @return array{chromosome: string, start: int, end: int, cm: float, snps: int}|null
     */
    private function buildSegment(string $chr, ?int $start, ?int $end, int $snps): ?array
    {
        if ($start === null || $snps < self::MIN_SNPS) {
            return null;
        }

        $cm = $this->estimateCm($start, $end);
        if ($cm < self::MIN_CM) {
            return null;
        }

        return [
            'chromosome' => $chr,
            'start' => $start,
            'end' => $end,
            'cm' => round($cm, 2),
            'snps' => $snps,
        ];
    }

    /**
     * Genetic length estimate from physical span.
     *
     * ponytail: physical-span * flat 1.0 cM/Mb is a genome-average
     * APPROXIMATION — recombination is not uniform, so this over-states cM in
     * cold regions and under-states it in hot ones. Upgrade path: load a genetic
     * recombination map (e.g. HapMap / deCODE per-chromosome cM positions) and
     * interpolate cM(end) - cM(start) instead of multiplying the base-pair span.
     */
    private function estimateCm(int $start, int $end): float
    {
        return (($end - $start) / 1_000_000) * self::CM_PER_MB;
    }
}
