<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('smart_matches', function (Blueprint $table) {
            $table->foreignId('record_type_id')->nullable()->after('match_source')->constrained('record_types')->nullOnDelete();
            $table->string('record_category')->nullable()->after('record_type_id'); // 'vital', 'census', 'newspaper', etc.
            $table->json('search_criteria')->nullable()->after('match_data'); // Store search parameters used
            $table->index('record_type_id');
            $table->index('record_category');
        });
    }

    public function down(): void
    {
        Schema::table('smart_matches', function (Blueprint $table) {
            $table->dropForeign(['record_type_id']);
            $table->dropColumn(['record_type_id', 'record_category', 'search_criteria']);
        });
    }
};
