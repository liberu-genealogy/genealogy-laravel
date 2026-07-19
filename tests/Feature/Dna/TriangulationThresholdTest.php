<?php

declare(strict_types=1);

namespace Tests\Feature\Dna;

use App\Filament\App\Pages\DnaTriangulationPage;
use App\Models\Dna;
use App\Models\DnaMatching;
use App\Models\User;
use App\Services\Dna\SegmentMatcher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * A minimum shared centimorgan threshold of zero asks for every pair regardless
 * of shared DNA, which has no genealogical meaning — triangulation is precisely
 * the business of finding kits that share segments.
 *
 * It was also load-bearing by accident: a kit that could not be read reports
 * 0.0 cM, so any positive threshold excluded it. At zero that filter stopped
 * working. The explicit comparison-performed guard now covers that case
 * independently, so correctness no longer rests on the threshold happening to
 * be greater than zero — but the input should still reject a value that cannot
 * mean anything.
 *
 * The floor is SegmentMatcher::MIN_CM, because a *non-zero* total is a sum of
 * segments that each cleared it. Zero is of course reportable — it is the value
 * being rejected — so every threshold in (0, 7] behaves identically to 7, and
 * offering finer values implies a precision the pipeline does not have.
 *
 * This is an input-sanity rule rather than a correctness fix. Uncompared pairs
 * are already excluded by the service's comparison-performed guard whatever the
 * threshold, which DnaTriangulationNoComparisonTest proves at a 0.0 threshold.
 */
class TriangulationThresholdTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_command_rejects_a_zero_threshold(): void
    {
        $kit = $this->kit('a', 'kit_a.txt');

        $this->artisan('dna:triangulate', ['base_kit_id' => $kit->id, '--min-cm' => 0])
            ->expectsOutputToContain('Minimum cM threshold must be at least')
            ->assertFailed();
    }

    public function test_the_command_rejects_a_negative_threshold(): void
    {
        $kit = $this->kit('a', 'kit_a.txt');

        $this->artisan('dna:triangulate', ['base_kit_id' => $kit->id, '--min-cm' => -5])
            ->assertFailed();
    }

    public function test_the_command_rejects_a_threshold_below_the_segment_floor(): void
    {
        $kit = $this->kit('a', 'kit_a.txt');

        $this->artisan('dna:triangulate', ['base_kit_id' => $kit->id, '--min-cm' => 3])
            ->assertFailed();
    }

    public function test_the_command_accepts_the_segment_floor_itself(): void
    {
        $kit = $this->kit('a', 'kit_a.txt');

        $this->artisan('dna:triangulate', ['base_kit_id' => $kit->id, '--min-cm' => SegmentMatcher::MIN_CM])
            ->assertSuccessful();
    }

    public function test_the_page_rejects_a_zero_threshold(): void
    {
        Storage::fake('private');

        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);

        $base = $this->kit('a', 'kit_a.txt', $user, withFile: true);
        $this->kit('b', 'kit_b.txt', $user, withFile: true);

        Livewire::test(DnaTriangulationPage::class)
            ->set('data.base_kit_id', $base->id)
            ->set('data.min_cm', 0)
            ->call('runTriangulation')
            ->assertHasErrors('data.min_cm');

        // The kits ARE readable here, so this proves validation stopped the run.
        // An earlier version used unreadable kits, where nothing would persist
        // regardless and the assertion could not fail.
        $this->assertSame(0, DnaMatching::withoutGlobalScopes()->count());
    }

    /**
     * The positive control. Without it, tightening minValue to something absurd
     * would leave every other test in this file green.
     */
    public function test_the_page_accepts_a_valid_threshold_and_stores_the_match(): void
    {
        Storage::fake('private');

        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);

        $base = $this->kit('a', 'kit_a.txt', $user, withFile: true);
        $this->kit('b', 'kit_b.txt', $user, withFile: true);

        Livewire::test(DnaTriangulationPage::class)
            ->set('data.base_kit_id', $base->id)
            ->set('data.min_cm', SegmentMatcher::MIN_CM)
            ->call('runTriangulation')
            ->assertHasNoErrors();

        // Two identical kits share their whole run, so this clears any threshold.
        $this->assertGreaterThan(0, DnaMatching::withoutGlobalScopes()->count());
    }

    /**
     * The compare-kits field says "Leave empty to compare against all kits".
     * An empty multi-select yields [], not null, and [] reaches
     * whereIn('id', []) — comparing against nothing. The helper text promised
     * the opposite of what happened.
     */
    public function test_leaving_compare_kits_empty_compares_against_all_kits(): void
    {
        Storage::fake('private');

        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);

        $base = $this->kit('a', 'kit_a.txt', $user, withFile: true);
        $this->kit('b', 'kit_b.txt', $user, withFile: true);

        Livewire::test(DnaTriangulationPage::class)
            ->set('data.base_kit_id', $base->id)
            ->set('data.compare_kit_ids', [])
            ->set('data.min_cm', SegmentMatcher::MIN_CM)
            ->call('runTriangulation')
            ->assertHasNoErrors();

        $this->assertGreaterThan(0, DnaMatching::withoutGlobalScopes()->count());
    }

    /**
     * The three-way path never reads the threshold, so it must not be rejected
     * for one. An earlier version of this guard sat before the branch and failed
     * a run for a value that path ignores.
     */
    public function test_three_way_triangulation_is_not_blocked_by_the_threshold(): void
    {
        Storage::fake('private');

        $a = $this->kit('a', 'kit_a.txt');
        $b = $this->kit('b', 'kit_b.txt');
        $c = $this->kit('c', 'kit_c.txt');

        $this->artisan('dna:triangulate', [
            'base_kit_id' => $a->id,
            '--three-way' => true,
            '--three-way-kits' => [$a->id, $b->id, $c->id],
            '--min-cm' => 0,
        ])->assertSuccessful();
    }

    private function kit(string $varName, string $fileName, ?User $owner = null, bool $withFile = false): Dna
    {
        if ($withFile) {
            Storage::disk('private')->put($fileName, $this->buildKit());
        }

        return Dna::create([
            'name' => $varName,
            'variable_name' => $varName,
            'file_name' => $fileName,
            'user_id' => ($owner ?? User::factory()->withPersonalTeam()->create())->id,
            'consent_given' => true,
            'consent_given_at' => now(),
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
