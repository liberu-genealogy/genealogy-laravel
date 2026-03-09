<?php

use Illuminate\Database\Migrations\Migration;

/**
 * The application now uses the "people" table (created by the
 * 2017_01_01_120000_create_people_table migration) as the single source of
 * truth for persons.  This migration is intentionally a no-op so that older
 * environments that already ran it are not affected.
 */
class CreatePersonsTable extends Migration
{
    public function up(): void
    {
        // No-op: the "people" table supersedes the former "persons" table.
    }

    public function down(): void
    {
        // No-op.
    }
}


