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
        if (! Schema::hasTable('persons')) {
            return;
        }

        // If the 'phone' column exists we keep the original intent and place
        // photo_url after it; otherwise add the column without the 'after'.
        if (Schema::hasColumn('persons', 'phone')) {
            Schema::table('persons', function (Blueprint $table) {
                $table->string('photo_url')->nullable()->after('phone');
            });
        } else {
            Schema::table('persons', function (Blueprint $table) {
                $table->string('photo_url')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('persons')) {
            return;
        }

        if (Schema::hasColumn('persons', 'photo_url')) {
            Schema::table('persons', function (Blueprint $table) {
                $table->dropColumn('photo_url');
            });
        }
    }
};
