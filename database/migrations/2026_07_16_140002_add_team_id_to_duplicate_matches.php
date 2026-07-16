<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('duplicate_matches', 'team_id')) {
            Schema::table('duplicate_matches', function (Blueprint $table): void {
                // nullable() MUST precede constrained() or the column ships NOT NULL (repo gotcha).
                $table->foreignId('team_id')->nullable()->constrained('teams')->nullOnDelete();
            });
        }

        // duplicate_matches has no user_id — its owner is the matched person, which
        // already carries team_id. Backfill from the primary person's team so
        // existing rows stay visible once BelongsToTenant's scope goes live.
        // Correlated subquery runs on both MySQL and SQLite. Rows whose primary
        // person has no team_id stay null (fail-closed).
        DB::statement(
            'UPDATE duplicate_matches SET team_id = '
            .'(SELECT team_id FROM people WHERE people.id = duplicate_matches.primary_person_id) '
            .'WHERE team_id IS NULL'
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('duplicate_matches', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('team_id');
        });
    }
};
