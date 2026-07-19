<?php

declare(strict_types=1);

namespace Tests\Feature\Commands;

use App\Models\Dna;
use App\Models\DnaMatching;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Batch matching computed every pair in a chunk, threw the results away, and
 * then dispatched jobs built from interpolated identifiers — "kit_5",
 * "file_5" — which match no stored kit. It also passed a Dna id to
 * User::find(). Every dispatched job therefore found nothing and returned, so
 * the command paid the full all-pairs cost, achieved nothing, and reported
 * "Successfully processed batch."
 *
 * It also read every kit in the table, consented or not, while the queued job
 * gates on consent twice.
 */
class ProcessLargeScaleDnaCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_persists_the_matches_it_computed(): void
    {
        Storage::fake('private');

        $a = $this->kit('a', 'kit_a.txt', withFile: true);
        $b = $this->kit('b', 'kit_b.txt', withFile: true);

        $this->artisan('dna:process-large-scale', ['--batch-size' => 10])
            ->assertSuccessful();

        // One comparison, stored in both directions the way the queued job does.
        $this->assertSame(2, DnaMatching::withoutGlobalScopes()->count());

        $match = DnaMatching::withoutGlobalScopes()->where('user_id', $a->user_id)->first();
        $this->assertNotNull($match);
        $this->assertSame($b->user_id, $match->match_id);
        $this->assertSame('kit_a.txt', $match->file1);
        $this->assertSame('kit_b.txt', $match->file2);
        $this->assertGreaterThan(0, (float) $match->total_shared_cm);

        // team_id is NOT mass-assignable by default; without it in $fillable the
        // row lands with a null tenant and never appears in the App panel.
        $this->assertSame($a->user->current_team_id, $match->team_id);
        $this->assertNotNull($match->team_id);

        // match_name is the first column of both match tables.
        $this->assertSame($b->user->name, $match->match_name);
    }

    /**
     * Asserts a non-zero count deliberately. The old command stored nothing at
     * all, so any test asserting "0 rows" passed against it trivially and
     * proved nothing. Three consented kits would give three pairs; the
     * unconsented one must reduce that to a single pair, not to zero.
     */
    public function test_kits_without_consent_are_never_compared(): void
    {
        Storage::fake('private');

        $a = $this->kit('a', 'kit_a.txt', withFile: true);
        $b = $this->kit('b', 'kit_b.txt', withFile: true);
        $this->kit('c', 'kit_c.txt', withFile: true, consented: false);

        $this->artisan('dna:process-large-scale')->assertSuccessful();

        $matches = DnaMatching::withoutGlobalScopes()->get();

        $this->assertCount(2, $matches, 'Only the two consented kits should have been paired.');
        $this->assertEqualsCanonicalizing(
            [$a->user_id, $b->user_id],
            $matches->pluck('user_id')->all(),
        );
    }

    /**
     * Same reasoning: one readable pair must still be stored, so the assertion
     * is not satisfiable by a command that simply stores nothing.
     */
    public function test_unreadable_kits_are_skipped_but_readable_ones_are_not(): void
    {
        Storage::fake('private');

        $this->kit('a', 'kit_a.txt', withFile: true);
        $this->kit('b', 'kit_b.txt', withFile: true);
        // A record with no file behind it — the ordinary failure case.
        $this->kit('c', 'kit_c.txt');

        $this->artisan('dna:process-large-scale')->assertSuccessful();

        // a<->b only, in both directions. Pairs involving c were not comparable.
        $this->assertSame(2, DnaMatching::withoutGlobalScopes()->count());
    }

    /**
     * dna_matchings carries no unique constraint, so an unconditional create()
     * would duplicate every row on a second run — and this is a command an
     * operator is expected to run repeatedly.
     */
    public function test_running_twice_does_not_duplicate_matches(): void
    {
        Storage::fake('private');

        $this->kit('a', 'kit_a.txt', withFile: true);
        $this->kit('b', 'kit_b.txt', withFile: true);

        $this->artisan('dna:process-large-scale')->assertSuccessful();
        $this->artisan('dna:process-large-scale')->assertSuccessful();

        $this->assertSame(2, DnaMatching::withoutGlobalScopes()->count());
    }

    /**
     * Comparisons used to be bounded by --batch-size: kits were only ever
     * compared against others in the same chunk. At the default of 10, an
     * installation with 1000 kits had 0.9% of its pairs examined (4,500 of
     * 499,500) while the command reported completion.
     *
     * Four kits at batch size 2 must still yield all six pairs.
     */
    public function test_pairs_are_compared_across_batch_boundaries(): void
    {
        Storage::fake('private');

        foreach (['a', 'b', 'c', 'd'] as $name) {
            $this->kit($name, "kit_{$name}.txt", withFile: true);
        }

        $this->artisan('dna:process-large-scale', ['--batch-size' => 2])
            ->assertSuccessful();

        // C(4,2) = 6 pairs, two rows each. Per-chunk pairing would give far fewer.
        $this->assertSame(12, DnaMatching::withoutGlobalScopes()->count());
    }

    public function test_it_reports_nothing_to_do_when_no_kit_has_consented(): void
    {
        Storage::fake('private');

        $this->kit('a', 'kit_a.txt', withFile: true, consented: false);

        $this->artisan('dna:process-large-scale')
            ->expectsOutputToContain('No consented DNA kits')
            ->assertSuccessful();
    }

    private function kit(string $varName, string $fileName, bool $withFile = false, bool $consented = true): Dna
    {
        if ($withFile) {
            Storage::disk('private')->put($fileName, $this->buildKit());
        }

        return Dna::create([
            'name' => $varName,
            'variable_name' => $varName,
            'file_name' => $fileName,
            'user_id' => User::factory()->withPersonalTeam()->create()->id,
            'consent_given' => $consented,
            'consent_given_at' => $consented ? now() : null,
        ]);
    }

    /**
     * Mirrors DnaMatchingPipelineTest's fixture: 600 SNPs on chr1 spanning
     * ~18 Mb, all heterozygous AG, so two copies are fully half-identical.
     */
    private function buildKit(): string
    {
        $lines = [
            '# This data file generated by 23andMe',
            "rsid\tchromosome\tposition\tgenotype",
        ];

        for ($i = 0; $i < 600; $i++) {
            $pos = 1_000_000 + $i * 30_000;
            $lines[] = "rs{$i}\t1\t{$pos}\tAG";
        }

        return implode("\n", $lines)."\n";
    }
}
