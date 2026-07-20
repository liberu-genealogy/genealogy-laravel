<?php

namespace App\Console\Commands;

use App\Models\Dna;
use App\Models\DnaMatching;
use App\Services\AdvancedDnaMatchingService;
use Illuminate\Console\Command;

/**
 * Manually match two DNA kits outside the queued DnaMatching job — for an
 * operator who needs to compare two specific kits on demand.
 *
 * The original command (deleted in 54a8d350) is not reproduced: it never set a
 * user id, so every insert threw, and its catch block invented centimorgans
 * with random_int() and tried to persist them. This one sets the owning user,
 * writes a row only when a real comparison ran, exits non-zero when it cannot
 * compare, and never constructs a match value in an error path.
 */
class MatchKitsCommand extends Command
{
    #[\Override]
    protected $signature = 'dna:match {kit1 : variable_name of the base kit (its owner owns the result)} {kit2 : variable_name of the kit to compare against}';

    #[\Override]
    protected $description = 'Match two DNA kits and store the result, owned by the base kit\'s user';

    public function handle(AdvancedDnaMatchingService $service): int
    {
        // Unauthenticated console context: BelongsToTenant does not scope, so a
        // plain lookup sees kits across all teams (which is what matching needs).
        $kit1 = Dna::where('variable_name', $this->argument('kit1'))->first();
        $kit2 = Dna::where('variable_name', $this->argument('kit2'))->first();

        if (! $kit1 || ! $kit2) {
            $this->error('One or both DNA kits not found.');

            return self::FAILURE;
        }

        // dnas.user_id is NOT NULL and FK-constrained, so both kits always have
        // an owner; the base kit's owner owns the stored result.
        $owner = $kit1->user;

        $result = $service->performAdvancedMatching(
            $kit1->variable_name, $kit1->file_name,
            $kit2->variable_name, $kit2->file_name,
        );

        // comparison_performed is false when a kit could not be read/parsed. The
        // result is a zeroed placeholder, not a measurement — persisting it would
        // record a comparison that never happened.
        if (! ($result['comparison_performed'] ?? false)) {
            $this->error('Kits could not be compared (a kit file could not be read or parsed). Nothing was stored.');

            return self::FAILURE;
        }

        $match = new DnaMatching;
        $match->user_id = $owner->id;
        // Console has no authenticated tenant; stamp the owner's team explicitly
        // so the row is not left team-less.
        $match->team_id = $owner->current_team_id;
        $match->match_id = $kit2->user_id;
        $match->match_name = $kit2->user->name ?? 'Unknown';
        // No visualisation/export files are produced here; image is nullable,
        // file1/file2 are NOT NULL.
        $match->image = null;
        $match->file1 = '';
        $match->file2 = '';
        $match->total_shared_cm = $result['total_cms'];
        $match->largest_cm_segment = $result['largest_cm'];
        $match->confidence_level = $result['confidence_level'] ?? null;
        $match->predicted_relationship = $result['predicted_relationship'] ?? null;
        $match->shared_segments_count = $result['shared_segments_count'] ?? null;
        $match->match_quality_score = $result['match_quality_score'] ?? null;
        $match->detailed_report = $result['detailed_report'] ?? null;
        $match->chromosome_breakdown = $result['chromosome_breakdown'] ?? null;
        $match->analysis_date = now();
        $match->save();

        $this->info("Stored match for {$kit1->variable_name} vs {$kit2->variable_name}: "
            ."{$match->total_shared_cm} cM, {$match->predicted_relationship}.");

        return self::SUCCESS;
    }
}
