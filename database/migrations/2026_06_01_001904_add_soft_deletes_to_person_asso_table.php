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
        Schema::table('person_asso', function (Blueprint $table) {
            if (! Schema::hasColumn('person_asso', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        Schema::table('person_asso', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
