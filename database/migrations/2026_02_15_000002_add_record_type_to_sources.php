<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sources', function (Blueprint $table) {
            $table->foreignId('record_type_id')->nullable()->after('id')->constrained('record_types')->nullOnDelete();
            $table->json('archive_metadata')->nullable()->after('text'); // Store type-specific metadata
            $table->index('record_type_id');
        });
    }

    public function down(): void
    {
        Schema::table('sources', function (Blueprint $table) {
            $table->dropForeign(['record_type_id']);
            $table->dropColumn(['record_type_id', 'archive_metadata']);
        });
    }
};
