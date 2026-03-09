<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('importjobs', function (Blueprint $table) {
            $table->unsignedTinyInteger('progress')->default(0)->after('status');
            $table->text('error_message')->nullable()->after('progress');
            $table->unsignedInteger('people_imported')->default(0)->after('error_message');
            $table->unsignedInteger('families_imported')->default(0)->after('people_imported');
        });
    }

    public function down(): void
    {
        Schema::table('importjobs', function (Blueprint $table) {
            $table->dropColumn(['progress', 'error_message', 'people_imported', 'families_imported']);
        });
    }
};
