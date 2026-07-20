<?php

namespace App\Console\Commands;

use App\Models\DnaMatching;
use App\Services\Dna\RelationshipEstimator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

/**
 * Removes dna_matchings rows that record a comparison which never happened.
 *
 * When a kit file could not be read, AdvancedDnaMatchingService did not throw —
 * it logged and returned performBasicMatching() under the label
 * "Unknown (Basic Analysis)". The queued job persisted that verbatim, wrote a
 * reciprocal row for the other user, and notified them that a new DNA match had
 * been found. The job no longer does this, but the rows it already wrote are
 * still in the database and still render as findings.
 *
 * Those rows are worse than they first appear. Until 6df2fd87 (2026-07-16)
 * performBasicMatching() did not return zeroes — it returned
 * random_int(10, 150) shared cM, random_int(5, 25) segments and a matching
 * largest-segment figure. That range is plausible third-to-fourth-cousin
 * territory, so a researcher had no way to tell an invented match from a real
 * one. Any database in service before that commit is likely to hold fabricated
 * genetic measurements under this label, not merely honest zeroes.
 *
 * The two zero-centimorgan cases are reliably distinguishable, which is what
 * makes this safe to automate:
 *
 *   no comparison ran   -> 'Unknown (Basic Analysis)'   (performBasicMatching)
 *   compared, 0 shared  -> 'Unrelated / No significant match'  (RelationshipEstimator)
 *
 * RelationshipEstimator cannot emit the former — its floor label is
 * 'Unrelated / No significant match' — so this is an exact marker, not a
 * heuristic on the cM value. Rows in the second group are genuine findings and
 * are deliberately left alone.
 *
 * Deletion rather than recomputation: a row asserting a comparison that did not
 * happen has no salvageable content, and the queued job will compare those kits
 * again on its next run if their files are now readable. Recomputing here would
 * duplicate that logic for no gain.
 *
 * This is a command rather than a migration on purpose. Migrations run
 * unattended on deploy, and this deletes rows a user may have seen; an operator
 * should be able to inspect the count with --dry-run first.
 */
class PruneNoComparisonMatchesCommand extends Command
{
    /**
     * Labels that mark a row as recording something other than a real
     * comparison.
     *
     * predicted_relationship on DnaMatchingResource is now a Select over the
     * estimator's own labels (RelationshipEstimator::labels()), which do not
     * include either marker below — so a user can no longer type one of these
     * strings into a hand-curated row and have it pruned. --dry-run and --export
     * remain for auditing what a run would remove.
     *
     * 'Unknown (Basic Analysis)' — performBasicMatching(), when a kit could not
     * be read. This is the population this command exists for.
     *
     * 'Unknown (Fallback Analysis)' — the removed MatchKitsCommand, which
     * invented centimorgans with random_int() in its catch block. It never set
     * user_id, which is NOT NULL, so every insert threw and no such row should
     * exist. It is included because if one somehow does, its values were
     * fabricated outright and it is the last thing that should survive a prune.
     */
    private const NO_COMPARISON_LABELS = [
        'Unknown (Basic Analysis)',
        'Unknown (Fallback Analysis)',
    ];

    #[\Override]
    protected $signature = 'dna:prune-no-comparison-matches
                            {--dry-run : Report what would be deleted without deleting it}
                            {--export= : Write the matched rows to this JSON path before deleting}
                            {--force : Skip the confirmation prompt}';

    #[\Override]
    protected $description = 'Delete DNA match rows recorded from comparisons that never ran';

    public function handle(): int
    {
        $query = DnaMatching::withoutGlobalScopes()
            ->whereIn('predicted_relationship', self::NO_COMPARISON_LABELS);

        $count = (clone $query)->count();

        // Reported for contrast so the operator can see the distinction being
        // drawn, and satisfy themselves that genuine findings are untouched.
        $genuineZeroes = DnaMatching::withoutGlobalScopes()
            ->where('predicted_relationship', RelationshipEstimator::NO_MATCH_LABEL)
            ->count();

        $this->reportUnclassified();

        if ($count === 0) {
            $this->info('No rows recorded from comparisons that never ran.');
            $this->line("Genuine 'compared, nothing shared' results left alone: {$genuineZeroes}");

            return Command::SUCCESS;
        }

        $this->warn("{$count} row(s) record a comparison that never ran.");
        $this->line("Genuine 'compared, nothing shared' results that will be kept: {$genuineZeroes}");

        if ($this->option('dry-run')) {
            $this->info("Dry run: {$count} row(s) would be deleted. Nothing was changed.");

            return Command::SUCCESS;
        }

        $this->warn('dna_matchings has no soft deletes — this cannot be undone.');

        if (! $this->option('force') && ! $this->confirm("Delete {$count} row(s)?")) {
            $this->info('Aborted. Nothing was changed.');

            return Command::SUCCESS;
        }

        // dna_matchings has no soft deletes, so this cannot be undone. Give the
        // operator a way to keep the evidence before it goes.
        if ($path = $this->option('export')) {
            File::put($path, (clone $query)->get()->toJson(JSON_PRETTY_PRINT));
            $this->info("Exported {$count} row(s) to {$path}");
        }

        $deleted = $query->delete();

        // Both directions of a pairing carry the same label, so a reciprocal row
        // is removed by the same predicate as its counterpart — no orphans.
        $this->info("Deleted {$deleted} row(s).");
        Log::info("Pruned {$deleted} DNA match row(s) recorded from comparisons that never ran.");

        // Users were notified when these rows were written. The notification
        // carries only a count, not the identity of the supposed match, so
        // there is nothing to correct beyond removing the rows themselves; a
        // user following an old notification simply finds nothing there.
        $this->line('Note: affected users may have received a "new DNA match" notification at the time.');

        return Command::SUCCESS;
    }

    /**
     * Rows whose label this command does not recognise are reported rather than
     * passed over in silence. A row with a NULL or unfamiliar
     * predicted_relationship might be a legitimate finding from an older
     * version, or another no-comparison result recorded under a label that no
     * longer exists in the code — this command cannot tell, so it says so
     * instead of guessing.
     */
    private function reportUnclassified(): void
    {
        $known = [...self::NO_COMPARISON_LABELS, ...RelationshipEstimator::labels()];

        $unclassified = DnaMatching::withoutGlobalScopes()
            ->where(function ($query) use ($known): void {
                $query->whereNull('predicted_relationship')
                    ->orWhereNotIn('predicted_relationship', $known);
            })
            ->count();

        if ($unclassified === 0) {
            return;
        }

        $this->warn("{$unclassified} row(s) carry a label this command does not recognise.");
        $this->line('They are left untouched. Older versions of the matching engine used labels');
        $this->line('that no longer exist in the code, so these cannot be classified automatically.');
    }
}
