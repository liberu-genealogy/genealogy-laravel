<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Fulltext indexes are only supported on MySQL/MariaDB
        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('people', function (Blueprint $table): void {
                $table->fullText(['givn', 'surn', 'name', 'description'], 'people_fulltext_index');
            });
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('people', function (Blueprint $table): void {
                $table->dropFullText('people_fulltext_index');
            });
        }
    }
};
