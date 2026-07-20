<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * media_objects_file's create migration only added id/form/medi/timestamps, but
 * the vendor MediaObjeectFile declares gid/group/type fillable and
 * MediaObject::files() filters on gid + group. Backfill the missing columns so
 * the relation (and the "Select GEDCOM Media" action) stops fatalling.
 * Types mirror media_objects (gid = integer, group = string).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('media_objects_file', function (Blueprint $table): void {
            if (! Schema::hasColumn('media_objects_file', 'gid')) {
                $table->integer('gid')->nullable();
            }
            if (! Schema::hasColumn('media_objects_file', 'group')) {
                $table->string('group')->nullable();
            }
            if (! Schema::hasColumn('media_objects_file', 'type')) {
                $table->string('type')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('media_objects_file', function (Blueprint $table): void {
            $table->dropColumn(['gid', 'group', 'type']);
        });
    }
};
