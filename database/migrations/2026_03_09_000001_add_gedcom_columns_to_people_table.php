<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds every GEDCOM-specific column required by the vendor GEDCOM parser to
 * the "people" table.  All additions are guarded with Schema::hasColumn() so
 * this migration is safe to run even if some columns were already added by
 * vendor migrations on an existing installation.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('people', function (Blueprint $table) {
            // GEDCOM identifier
            if (!Schema::hasColumn('people', 'gid')) {
                $table->string('gid')->nullable();
            }

            // Name components
            if (!Schema::hasColumn('people', 'givn')) {
                $table->string('givn')->nullable()->index();
            }
            if (!Schema::hasColumn('people', 'surn')) {
                $table->string('surn', 191)->nullable()->index();
            }
            if (!Schema::hasColumn('people', 'npfx')) {
                $table->string('npfx')->nullable();
            }
            if (!Schema::hasColumn('people', 'nick')) {
                $table->string('nick')->nullable();
            }
            if (!Schema::hasColumn('people', 'spfx')) {
                $table->string('spfx')->nullable();
            }
            if (!Schema::hasColumn('people', 'nsfx')) {
                $table->string('nsfx')->nullable();
            }
            if (!Schema::hasColumn('people', 'type')) {
                $table->string('type')->nullable();
            }
            if (!Schema::hasColumn('people', 'titl')) {
                $table->string('titl')->nullable();
            }

            // Demographics
            if (!Schema::hasColumn('people', 'sex')) {
                $table->char('sex', 1)->nullable();
            }

            // Contact & media
            if (!Schema::hasColumn('people', 'photo_url')) {
                $table->string('photo_url')->nullable();
            }

            // Birth details
            if (!Schema::hasColumn('people', 'birth_month')) {
                $table->string('birth_month')->nullable();
            }
            if (!Schema::hasColumn('people', 'birth_year')) {
                $table->integer('birth_year')->nullable();
            }
            if (!Schema::hasColumn('people', 'birthday_dati')) {
                $table->string('birthday_dati')->nullable();
            }
            if (!Schema::hasColumn('people', 'birthday_plac')) {
                $table->string('birthday_plac')->nullable();
            }

            // Death
            if (!Schema::hasColumn('people', 'deathday')) {
                $table->date('deathday')->nullable();
            }
            if (!Schema::hasColumn('people', 'death_month')) {
                $table->string('death_month')->nullable();
            }
            if (!Schema::hasColumn('people', 'death_year')) {
                $table->integer('death_year')->nullable();
            }
            if (!Schema::hasColumn('people', 'deathday_dati')) {
                $table->string('deathday_dati')->nullable();
            }
            if (!Schema::hasColumn('people', 'deathday_plac')) {
                $table->string('deathday_plac')->nullable();
            }
            if (!Schema::hasColumn('people', 'deathday_caus')) {
                $table->string('deathday_caus')->nullable();
            }

            // Burial
            if (!Schema::hasColumn('people', 'burial_day')) {
                $table->date('burial_day')->nullable();
            }
            if (!Schema::hasColumn('people', 'burial_month')) {
                $table->string('burial_month')->nullable();
            }
            if (!Schema::hasColumn('people', 'burial_year')) {
                $table->integer('burial_year')->nullable();
            }
            if (!Schema::hasColumn('people', 'burial_day_dati')) {
                $table->string('burial_day_dati')->nullable();
            }
            if (!Schema::hasColumn('people', 'burial_day_plac')) {
                $table->string('burial_day_plac')->nullable();
            }

            // Christening
            if (!Schema::hasColumn('people', 'chr')) {
                $table->string('chr')->nullable();
            }

            // Family relationships
            if (!Schema::hasColumn('people', 'child_in_family_id')) {
                $table->integer('child_in_family_id')->nullable();
            }
            if (!Schema::hasColumn('people', 'famc')) {
                $table->string('famc')->nullable();
            }
            if (!Schema::hasColumn('people', 'fams')) {
                $table->string('fams')->nullable();
            }

            // Miscellaneous
            if (!Schema::hasColumn('people', 'description')) {
                $table->text('description')->nullable();
            }
            if (!Schema::hasColumn('people', 'chan')) {
                $table->string('chan')->nullable();
            }
            if (!Schema::hasColumn('people', 'rin')) {
                $table->string('rin')->nullable();
            }
            if (!Schema::hasColumn('people', 'resn')) {
                $table->string('resn')->nullable();
            }
            if (!Schema::hasColumn('people', 'rfn')) {
                $table->string('rfn')->nullable();
            }
            if (!Schema::hasColumn('people', 'afn')) {
                $table->string('afn')->nullable();
            }

            // Application-specific
            if (!Schema::hasColumn('people', 'first_name')) {
                $table->string('first_name')->nullable();
            }
            if (!Schema::hasColumn('people', 'last_name')) {
                $table->string('last_name')->nullable();
            }

            // Soft deletes
            if (!Schema::hasColumn('people', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        Schema::table('people', function (Blueprint $table) {
            $table->dropColumn(array_filter([
                Schema::hasColumn('people', 'gid') ? 'gid' : null,
                Schema::hasColumn('people', 'givn') ? 'givn' : null,
                Schema::hasColumn('people', 'surn') ? 'surn' : null,
                Schema::hasColumn('people', 'npfx') ? 'npfx' : null,
                Schema::hasColumn('people', 'nick') ? 'nick' : null,
                Schema::hasColumn('people', 'spfx') ? 'spfx' : null,
                Schema::hasColumn('people', 'nsfx') ? 'nsfx' : null,
                Schema::hasColumn('people', 'type') ? 'type' : null,
                Schema::hasColumn('people', 'titl') ? 'titl' : null,
                Schema::hasColumn('people', 'sex') ? 'sex' : null,
                Schema::hasColumn('people', 'photo_url') ? 'photo_url' : null,
                Schema::hasColumn('people', 'birth_month') ? 'birth_month' : null,
                Schema::hasColumn('people', 'birth_year') ? 'birth_year' : null,
                Schema::hasColumn('people', 'birthday_dati') ? 'birthday_dati' : null,
                Schema::hasColumn('people', 'birthday_plac') ? 'birthday_plac' : null,
                Schema::hasColumn('people', 'deathday') ? 'deathday' : null,
                Schema::hasColumn('people', 'death_month') ? 'death_month' : null,
                Schema::hasColumn('people', 'death_year') ? 'death_year' : null,
                Schema::hasColumn('people', 'deathday_dati') ? 'deathday_dati' : null,
                Schema::hasColumn('people', 'deathday_plac') ? 'deathday_plac' : null,
                Schema::hasColumn('people', 'deathday_caus') ? 'deathday_caus' : null,
                Schema::hasColumn('people', 'burial_day') ? 'burial_day' : null,
                Schema::hasColumn('people', 'burial_month') ? 'burial_month' : null,
                Schema::hasColumn('people', 'burial_year') ? 'burial_year' : null,
                Schema::hasColumn('people', 'burial_day_dati') ? 'burial_day_dati' : null,
                Schema::hasColumn('people', 'burial_day_plac') ? 'burial_day_plac' : null,
                Schema::hasColumn('people', 'chr') ? 'chr' : null,
                Schema::hasColumn('people', 'child_in_family_id') ? 'child_in_family_id' : null,
                Schema::hasColumn('people', 'famc') ? 'famc' : null,
                Schema::hasColumn('people', 'fams') ? 'fams' : null,
                Schema::hasColumn('people', 'description') ? 'description' : null,
                Schema::hasColumn('people', 'chan') ? 'chan' : null,
                Schema::hasColumn('people', 'rin') ? 'rin' : null,
                Schema::hasColumn('people', 'resn') ? 'resn' : null,
                Schema::hasColumn('people', 'rfn') ? 'rfn' : null,
                Schema::hasColumn('people', 'afn') ? 'afn' : null,
                Schema::hasColumn('people', 'first_name') ? 'first_name' : null,
                Schema::hasColumn('people', 'last_name') ? 'last_name' : null,
                Schema::hasColumn('people', 'deleted_at') ? 'deleted_at' : null,
            ]));
        });
    }
};
