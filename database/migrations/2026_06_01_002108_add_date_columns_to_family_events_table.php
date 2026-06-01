<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('family_events', function (Blueprint $table) {
            if (! Schema::hasColumn('family_events', 'year')) {
                $table->integer('year')->nullable();
            }
            if (! Schema::hasColumn('family_events', 'month')) {
                $table->integer('month')->nullable();
            }
            if (! Schema::hasColumn('family_events', 'day')) {
                $table->integer('day')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('family_events', function (Blueprint $table) {
            foreach (['year', 'month', 'day'] as $col) {
                if (Schema::hasColumn('family_events', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
