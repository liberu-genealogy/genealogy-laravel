<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * person_events and subms were meant to have an optional addr_id, but their
 * create migrations chained ->constrained('addrs')->nullable(): with nullable()
 * *after* constrained() it modifies the foreign-key object, not the column, so
 * the column shipped NOT NULL. Every event/subm insert that omits an address
 * then died with SQLSTATE[HY000] 1364 Field 'addr_id' doesn't have a default
 * value. repositories wrote ->nullable()->constrained() (correct order) and is
 * fine. This makes the column match the original intent.
 */
return new class extends Migration
{
    public function up(): void
    {
        foreach (['person_events', 'subms'] as $table) {
            Schema::table($table, function (Blueprint $t): void {
                $t->unsignedBigInteger('addr_id')->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        // Not reverting to NOT NULL: existing rows may legitimately have a null
        // addr_id now, which a NOT NULL constraint would reject.
    }
};
