<?php

declare(strict_types=1);

namespace Tests\Feature\Commands;

use App\Models\DnaMatching;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Before the matching job was fixed, an unreadable kit produced a durable
 * dna_matchings row: the service's zeroed "basic analysis" result persisted
 * verbatim, plus a reciprocal row for the other user, plus a notification
 * saying a new DNA match had been found. Those rows are still in the database.
 *
 * They are distinguishable from a genuine finding of nothing shared, which is
 * the whole reason this can be done safely:
 *
 *   no comparison ran   -> 'Unknown (Basic Analysis)'  (performBasicMatching)
 *   compared, 0 shared  -> 'Unrelated / No significant match'  (RelationshipEstimator)
 *
 * RelationshipEstimator never emits the former, so the label is a reliable
 * marker rather than a heuristic.
 */
class PruneNoComparisonMatchesTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_deletes_rows_where_no_comparison_ran(): void
    {
        $this->match('Unknown (Basic Analysis)', 0.0);

        $this->artisan('dna:prune-no-comparison-matches', ['--force' => true])
            ->assertSuccessful();

        $this->assertSame(0, DnaMatching::withoutGlobalScopes()->count());
    }

    /**
     * The removed MatchKitsCommand used a different label and fabricated its
     * centimorgans with random_int(). It never persisted a row — user_id is
     * NOT NULL and it never set it — but if one exists it is invented data.
     */
    public function test_it_deletes_rows_from_the_removed_fabricating_command(): void
    {
        $this->match('Unknown (Fallback Analysis)', 87.0);

        $this->artisan('dna:prune-no-comparison-matches', ['--force' => true])
            ->assertSuccessful();

        $this->assertSame(0, DnaMatching::withoutGlobalScopes()->count());
    }

    public function test_it_keeps_a_genuine_finding_of_nothing_shared(): void
    {
        $this->match('Unrelated / No significant match', 0.0);

        $this->artisan('dna:prune-no-comparison-matches', ['--force' => true])
            ->assertSuccessful();

        // Zero shared centimorgans is a real result when a comparison actually
        // ran. Deleting it would destroy a finding.
        $this->assertSame(1, DnaMatching::withoutGlobalScopes()->count());
    }

    public function test_it_keeps_real_matches(): void
    {
        $this->match('Second Cousin', 212.0);

        $this->artisan('dna:prune-no-comparison-matches', ['--force' => true])
            ->assertSuccessful();

        $this->assertSame(1, DnaMatching::withoutGlobalScopes()->count());
    }

    /**
     * The job wrote both directions with the same values, so both carry the
     * marker and both must go. A surviving reciprocal would leave one party
     * still looking at a match that never happened.
     */
    public function test_reciprocal_rows_are_removed_together(): void
    {
        $a = User::factory()->withPersonalTeam()->create();
        $b = User::factory()->withPersonalTeam()->create();

        $this->match('Unknown (Basic Analysis)', 0.0, $a, $b);
        $this->match('Unknown (Basic Analysis)', 0.0, $b, $a);

        $this->artisan('dna:prune-no-comparison-matches', ['--force' => true])
            ->assertSuccessful();

        $this->assertSame(0, DnaMatching::withoutGlobalScopes()->count());
    }

    /**
     * A pairing where only one side carries the marker — reachable if the
     * second save() failed, or via the free-text relationship field on
     * DnaMatchingResource. The marked row goes; the unmarked one is a genuine
     * finding and must not be swept up with it.
     */
    public function test_only_the_marked_side_of_a_pairing_is_deleted(): void
    {
        $a = User::factory()->withPersonalTeam()->create();
        $b = User::factory()->withPersonalTeam()->create();

        $this->match('Unknown (Basic Analysis)', 0.0, $a, $b);
        $kept = $this->match('2nd-3rd Cousin', 120.0, $b, $a);

        $this->artisan('dna:prune-no-comparison-matches', ['--force' => true])
            ->assertSuccessful();

        $rows = DnaMatching::withoutGlobalScopes()->get();
        $this->assertCount(1, $rows);
        $this->assertSame($kept->id, $rows->first()->id);
    }

    /**
     * A label from an older matching engine is neither prunable nor a current
     * estimator label. It must survive and be reported, not silently ignored.
     */
    public function test_an_unrecognised_label_is_kept_and_reported(): void
    {
        $this->match('No significant relationship detected', 0.0);

        $this->artisan('dna:prune-no-comparison-matches', ['--force' => true])
            ->expectsOutputToContain('does not recognise')
            ->assertSuccessful();

        $this->assertSame(1, DnaMatching::withoutGlobalScopes()->count());
    }

    /**
     * The confirmation prompt is the only thing between a mistaken invocation
     * and permanent loss, so declining it needs to be proven to do nothing.
     */
    public function test_declining_the_confirmation_deletes_nothing(): void
    {
        $this->match('Unknown (Basic Analysis)', 0.0);

        $this->artisan('dna:prune-no-comparison-matches')
            ->expectsConfirmation('Delete 1 row(s)?', 'no')
            ->expectsOutputToContain('Aborted')
            ->assertSuccessful();

        $this->assertSame(1, DnaMatching::withoutGlobalScopes()->count());
    }

    public function test_it_exports_the_rows_before_deleting_them(): void
    {
        $this->match('Unknown (Basic Analysis)', 0.0);
        $path = storage_path('app/pruned-matches-test.json');
        @unlink($path);

        $this->artisan('dna:prune-no-comparison-matches', ['--force' => true, '--export' => $path])
            ->assertSuccessful();

        $this->assertFileExists($path);
        $exported = json_decode(file_get_contents($path), true);
        $this->assertCount(1, $exported);
        $this->assertSame('Unknown (Basic Analysis)', $exported[0]['predicted_relationship']);

        $this->assertSame(0, DnaMatching::withoutGlobalScopes()->count());
        @unlink($path);
    }

    public function test_dry_run_reports_without_deleting(): void
    {
        $this->match('Unknown (Basic Analysis)', 0.0);
        $this->match('Second Cousin', 212.0);

        $this->artisan('dna:prune-no-comparison-matches', ['--dry-run' => true])
            ->expectsOutputToContain('1 row(s) would be deleted')
            ->assertSuccessful();

        $this->assertSame(2, DnaMatching::withoutGlobalScopes()->count());
    }

    public function test_it_reports_when_there_is_nothing_to_do(): void
    {
        $this->match('Second Cousin', 212.0);

        $this->artisan('dna:prune-no-comparison-matches', ['--force' => true])
            ->expectsOutputToContain('No rows')
            ->assertSuccessful();

        $this->assertSame(1, DnaMatching::withoutGlobalScopes()->count());
    }

    /**
     * A row this command cannot classify must survive and be reported, not be
     * swept up by a looser predicate such as "zero centimorgans".
     */
    public function test_unclassifiable_rows_are_kept_and_reported(): void
    {
        $this->match(null, 0.0);

        $this->artisan('dna:prune-no-comparison-matches', ['--force' => true])
            ->expectsOutputToContain('cannot be classified')
            ->assertSuccessful();

        $this->assertSame(1, DnaMatching::withoutGlobalScopes()->count());
    }

    private function match(?string $relationship, float $cm, ?User $owner = null, ?User $other = null): DnaMatching
    {
        $owner ??= User::factory()->withPersonalTeam()->create();
        $other ??= User::factory()->withPersonalTeam()->create();

        return DnaMatching::withoutGlobalScopes()->create([
            'team_id' => $owner->current_team_id,
            'user_id' => $owner->id,
            'match_id' => $other->id,
            'match_name' => $other->name,
            'file1' => 'kit_a.txt',
            'file2' => 'kit_b.txt',
            'total_shared_cm' => $cm,
            'largest_cm_segment' => $cm > 0 ? 45.0 : 0.0,
            'predicted_relationship' => $relationship,
            'analysis_date' => now(),
        ]);
    }
}
