<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * De-duplicate the families table. The GEDCOM importer's
 * updateOrCreate($value, $value) keys on the whole row, so re-importing a tree
 * never matches and re-inserts every couple — the dev DB held each couple 2-6×.
 *
 * Collapse each duplicate (husband_id, wife_id, team_id) group with BOTH parents
 * set to its lowest id, repointing the child / event / sealing FKs, then add a
 * unique index so a future re-import fails loud instead of silently duplicating.
 *
 * Single-parent rows (a null husband or wife) are intentionally left alone: a
 * unique index treats NULLs as distinct, which is exactly the "both parents
 * present" scope we want — MySQL/MariaDB has no partial-index WHERE clause, and
 * this null behaviour gives it for free. Two distinct marriages of the same pair
 * would now collide; that rare case is the accepted cost (see ticket 05).
 */
return new class extends Migration
{
    public function up(): void
    {
        $groups = DB::table('families')
            ->select('husband_id', 'wife_id', 'team_id', DB::raw('MIN(id) as canonical_id'))
            ->whereNotNull('husband_id')
            ->whereNotNull('wife_id')
            ->groupBy('husband_id', 'wife_id', 'team_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($groups as $group) {
            $extraIds = DB::table('families')
                ->where('husband_id', $group->husband_id)
                ->where('wife_id', $group->wife_id)
                ->where('team_id', $group->team_id)
                ->where('id', '!=', $group->canonical_id)
                ->pluck('id');

            if ($extraIds->isEmpty()) {
                continue;
            }

            DB::table('people')->whereIn('child_in_family_id', $extraIds)
                ->update(['child_in_family_id' => $group->canonical_id]);

            if (Schema::hasTable('family_events')) {
                DB::table('family_events')->whereIn('family_id', $extraIds)
                    ->update(['family_id' => $group->canonical_id]);
            }

            if (Schema::hasTable('family_slgs')) {
                DB::table('family_slgs')->whereIn('family_id', $extraIds)
                    ->update(['family_id' => $group->canonical_id]);
            }

            DB::table('families')->whereIn('id', $extraIds)->delete();
        }

        Schema::table('families', function (Blueprint $table): void {
            $table->unique(['husband_id', 'wife_id', 'team_id'], 'families_husband_wife_team_unique');
        });
    }

    public function down(): void
    {
        Schema::table('families', function (Blueprint $table): void {
            $table->dropUnique('families_husband_wife_team_unique');
        });
        // Collapsed rows are not restored — the merge is irreversible.
    }
};
