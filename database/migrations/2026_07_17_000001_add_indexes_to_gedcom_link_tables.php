<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * `person_asso` and `source_ref` are GEDCOM's pseudo-polymorphic link tables:
 * (`group`, `gid`) identifies the record a row hangs off. Until now they were
 * write-only import artifacts, so neither carried an index beyond the primary
 * key and the team_id FK. Person::associations() / Person::sourceRefs() make
 * (group, gid) the hot lookup path, and these tables grow one row per citation
 * per person — a full scan per person page is not viable on a real tree.
 *
 * Index names are given explicitly: MySQL caps identifiers at 64 chars and
 * errors 1059 on longer auto-generated names, which SQLite would not catch.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('person_asso', function (Blueprint $table): void {
            $table->index(['group', 'gid'], 'person_asso_group_gid_idx');
            // The reverse lookup: "who is associated WITH this person".
            $table->index('indi', 'person_asso_indi_idx');
        });

        Schema::table('source_ref', function (Blueprint $table): void {
            $table->index(['group', 'gid'], 'source_ref_group_gid_idx');
            // "which records cite this source"
            $table->index('sour_id', 'source_ref_sour_id_idx');
        });
    }

    public function down(): void
    {
        Schema::table('person_asso', function (Blueprint $table): void {
            $table->dropIndex('person_asso_group_gid_idx');
            $table->dropIndex('person_asso_indi_idx');
        });

        Schema::table('source_ref', function (Blueprint $table): void {
            $table->dropIndex('source_ref_group_gid_idx');
            $table->dropIndex('source_ref_sour_id_idx');
        });
    }
};
