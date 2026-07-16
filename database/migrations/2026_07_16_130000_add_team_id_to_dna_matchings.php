<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('dna_matchings', 'team_id')) {
            Schema::table('dna_matchings', function (Blueprint $table): void {
                // nullable() MUST precede constrained() or the column ships NOT NULL (repo gotcha).
                $table->foreignId('team_id')->nullable()->constrained('teams')->nullOnDelete();
            });
        }

        // Backfill from the owning user's current team so existing rows stay
        // visible to their owner instead of becoming orphaned once the tenant
        // scope goes live (a null team_id matches no tenant). Correlated
        // subquery form runs on both MySQL and SQLite. Rows whose user has no
        // current_team_id stay null (fail-closed).
        DB::statement(
            'UPDATE dna_matchings SET team_id = '
            .'(SELECT current_team_id FROM users WHERE users.id = dna_matchings.user_id) '
            .'WHERE team_id IS NULL'
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dna_matchings', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('team_id');
        });
    }
};
