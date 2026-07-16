<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::table('dnas', function (Blueprint $table): void {
            if (!Schema::hasColumn('dnas', 'consent_given')) {
                // Existing rows default to no-consent; they must be backfilled or
                // re-consented before matching. Server-side enforcement is separate.
                $table->boolean('consent_given')->default(false);
            }
            if (!Schema::hasColumn('dnas', 'consent_given_at')) {
                $table->timestamp('consent_given_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('dnas', function (Blueprint $table): void {
            foreach (['consent_given', 'consent_given_at'] as $column) {
                if (Schema::hasColumn('dnas', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
