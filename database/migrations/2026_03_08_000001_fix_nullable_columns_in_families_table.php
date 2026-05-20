<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The original create_families_table migration used the incorrect pattern
 * `->constrained()->nullable()` which does NOT propagate nullable() to the
 * column (constrained() returns a ForeignKeyDefinition, not a ColumnDefinition).
 * This resulted in husband_id, wife_id, and type_id being NOT NULL in the
 * database despite the developer's intent, causing integrity constraint
 * violations when creating families with null spouses (which is valid in
 * GEDCOM files and in the FamilyFactory).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('families', function (Blueprint $table) {
            $table->unsignedBigInteger('type_id')->nullable()->change();
            $table->unsignedBigInteger('husband_id')->nullable()->change();
            $table->unsignedBigInteger('wife_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('families', function (Blueprint $table) {
            $table->unsignedBigInteger('type_id')->nullable(false)->change();
            $table->unsignedBigInteger('husband_id')->nullable(false)->change();
            $table->unsignedBigInteger('wife_id')->nullable(false)->change();
        });
    }
};
