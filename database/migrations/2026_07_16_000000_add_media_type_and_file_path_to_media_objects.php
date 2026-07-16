<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('media_objects', function (Blueprint $table): void {
            if (!Schema::hasColumn('media_objects', 'media_type')) {
                $table->string('media_type')->nullable();
            }

            if (!Schema::hasColumn('media_objects', 'file_path')) {
                $table->string('file_path')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('media_objects', function (Blueprint $table): void {
            $table->dropColumn(['media_type', 'file_path']);
        });
    }
};
