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
        if (! Schema::hasColumn('duplicate_checks', 'team_id')) {
            Schema::table('duplicate_checks', function (Blueprint $table): void {
                // nullable() MUST precede constrained() or the column ships NOT NULL (repo gotcha).
                $table->foreignId('team_id')->nullable()->constrained('teams')->nullOnDelete();
            });
        }

        // Backfill from the owning user's current team so existing rows stay
        // visible once BelongsToTenant's scope goes live (null team_id matches no
        // tenant). Correlated subquery runs on both MySQL and SQLite. Rows whose
        // user has no current_team_id stay null (fail-closed).
        DB::statement(
            'UPDATE duplicate_checks SET team_id = '
            .'(SELECT current_team_id FROM users WHERE users.id = duplicate_checks.user_id) '
            .'WHERE team_id IS NULL'
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('duplicate_checks', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('team_id');
        });
    }
};
