<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('people')) {
            return;
        }

        // photo_url is added by 2026_03_09_000001_add_gedcom_columns_to_people_table if
        // it doesn't already exist, so this migration is a no-op when that column is present.
        if (Schema::hasColumn('people', 'photo_url')) {
            return;
        }

        // If the 'phone' column exists we keep the original intent and place
        // photo_url after it; otherwise add the column without the 'after'.
        if (Schema::hasColumn('people', 'phone')) {
            Schema::table('people', function (Blueprint $table) {
                $table->string('photo_url')->nullable()->after('phone');
            });
        } else {
            Schema::table('people', function (Blueprint $table) {
                $table->string('photo_url')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('people')) {
            return;
        }

        if (Schema::hasColumn('people', 'photo_url')) {
            Schema::table('people', function (Blueprint $table) {
                $table->dropColumn('photo_url');
            });
        }
    }
};
