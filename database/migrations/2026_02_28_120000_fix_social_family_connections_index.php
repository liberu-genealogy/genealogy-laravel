<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // this migration is maintenance-only and may confuse sqlite-based
        // test databases; skip completely when running tests to avoid
        // hitting INFORMATION_SCHEMA or other MySQL-specific logic.
        if (app()->environment('testing')) {
            return;
        }

        // also, only execute on an actual MySQL connection in non-test runs.
        if (config('database.default') !== 'mysql' || DB::getDriverName() !== 'mysql') {
            return;
        }

        if (Schema::hasTable('social_family_connections')) {
            // Determine if we're on MySQL so we can query INFORMATION_SCHEMA
            $useInfoSchema = DB::getDriverName() === 'mysql';

            if ($useInfoSchema) {
                $dbName = DB::getDatabaseName();
                try {
                    $exists = DB::selectOne(
                        'SELECT 1 FROM INFORMATION_SCHEMA.STATISTICS
                            WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND INDEX_NAME = ?',
                        [$dbName, 'social_family_connections', 'sfc_account_social_id_idx']
                    );
                } catch (\Exception $e) {
                    // safe to ignore if the query fails for any reason
                    $exists = false;
                }
            } else {
                $exists = false;
            }

            if (!$exists) {
                try {
                    Schema::table('social_family_connections', function (Blueprint $table) {
                        // the previously-added index already has the correct short name.
                        // dropping it can fail if MySQL is using it for the foreign-key
                        // on connected_account_id, so we simply avoid removing it here.
                        $table->index(
                            ['connected_account_id', 'matched_social_id'],
                            'sfc_account_social_id_idx'
                        );
                    });
                } catch (\Illuminate\Database\QueryException $e) {
                    // ignore duplicate-key errors (1061) which can happen if the
                    // index already exists but we couldn't detect it earlier.
                    $mysqlCode = $e->errorInfo[1] ?? null;
                    if ($mysqlCode !== 1061) {
                        throw $e;
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('social_family_connections')) {
            Schema::table('social_family_connections', function (Blueprint $table) {
                // simply remove the fixed index; the original long-named index
                // cannot be created on MySQL, so we don't attempt to recreate it
                // during rollback. This keeps the rollback safe and idempotent.
                $table->dropIndex('sfc_account_social_id_idx');
            });
        }
    }
};
