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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('total_points')->default(0)->after('premium_started_at');
            $table->integer('level')->default(1)->after('total_points');
            $table->integer('level_progress')->default(0)->after('level'); // Progress towards next level
            $table->timestamp('last_activity_at')->nullable()->after('level_progress');
            $table->boolean('show_on_leaderboard')->default(true)->after('last_activity_at');

            $table->index(['total_points', 'show_on_leaderboard']);
            $table->index(['level', 'level_progress']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'total_points',
                'level',
                'level_progress',
                'last_activity_at',
                'show_on_leaderboard'
            ]);
        });
    }
};