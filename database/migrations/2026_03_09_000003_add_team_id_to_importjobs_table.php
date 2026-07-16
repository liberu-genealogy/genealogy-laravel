<?php

use App\Models\Team;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('importjobs', function (Blueprint $table): void {
            $table->foreignId('team_id')->nullable()->constrained('teams')->nullOnDelete()->after('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('importjobs', function (Blueprint $table): void {
            $table->dropForeignIdFor(Team::class);
            $table->dropColumn('team_id');
        });
    }
};
