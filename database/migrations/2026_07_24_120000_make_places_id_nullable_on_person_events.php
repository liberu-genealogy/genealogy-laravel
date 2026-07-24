<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Same bug as the addr_id fix (2026_07_16): person_events.places_id was created
 * with ->constrained('places')->nullable() — nullable() *after* constrained()
 * modifies the FK object, not the column, so places_id shipped NOT NULL. The
 * GEDCOM import never sets places_id, so the vendor's PersonEvent insert died
 * with a NOT NULL violation, aborting the whole event/birth-date import (every
 * BIRT/DEAT/EVEN was silently lost). Making the column nullable lets the import
 * write person_events rows and set person.birthday again.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('person_events', function (Blueprint $t): void {
            $t->unsignedBigInteger('places_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Not reverting: existing rows may legitimately have a null places_id.
    }
};
