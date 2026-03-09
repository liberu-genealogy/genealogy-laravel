<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ensure husband_id and wife_id on the families table are nullable.
     *
     * The original create_families_table migration used
     * ->foreignId('husband_id')->constrained('people')->nullable(), but
     * calling nullable() after constrained() may not propagate correctly
     * in all Laravel/MySQL combinations, leaving the column as NOT NULL.
     * This migration explicitly alters both columns to be nullable.
     */
    public function up(): void
    {
        Schema::table('families', function (Blueprint $table) {
            $table->unsignedBigInteger('husband_id')->nullable()->change();
            $table->unsignedBigInteger('wife_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Reverting to NOT NULL would break existing rows with null values,
        // so this is intentionally left as a no-op.
    }
};
