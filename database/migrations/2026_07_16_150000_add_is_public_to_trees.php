<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('trees', 'is_public')) {
            Schema::table('trees', function (Blueprint $table): void {
                // Private by default (SCOPE §20): a tree is only shared once its owner opts in.
                $table->boolean('is_public')->default(false);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trees', function (Blueprint $table): void {
            $table->dropColumn('is_public');
        });
    }
};
