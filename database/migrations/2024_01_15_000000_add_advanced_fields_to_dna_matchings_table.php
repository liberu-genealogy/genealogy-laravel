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
        Schema::table('dna_matchings', function (Blueprint $table) {
            $table->float('confidence_level')->nullable()->after('largest_cm_segment');
            $table->string('predicted_relationship')->nullable()->after('confidence_level');
            $table->integer('shared_segments_count')->nullable()->after('predicted_relationship');
            $table->float('match_quality_score')->nullable()->after('shared_segments_count');
            $table->json('detailed_report')->nullable()->after('match_quality_score');
            $table->json('chromosome_breakdown')->nullable()->after('detailed_report');
            $table->timestamp('analysis_date')->nullable()->after('chromosome_breakdown');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dna_matchings', function (Blueprint $table) {
            $table->dropColumn([
                'confidence_level',
                'predicted_relationship',
                'shared_segments_count',
                'match_quality_score',
                'detailed_report',
                'chromosome_breakdown',
                'analysis_date',
            ]);
        });
    }
};