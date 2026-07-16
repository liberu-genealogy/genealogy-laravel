<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * GEDCOM FAMC.PEDI: the child's link-type to its child_in_family_id family
 * (birth / adopted / foster / sealing). Null = biological (the standard case).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('people', function (Blueprint $table): void {
            if (!Schema::hasColumn('people', 'pedigree')) {
                $table->string('pedigree')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('people', function (Blueprint $table): void {
            if (Schema::hasColumn('people', 'pedigree')) {
                $table->dropColumn('pedigree');
            }
        });
    }
};
