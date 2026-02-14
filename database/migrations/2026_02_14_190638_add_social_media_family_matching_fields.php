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
        // Add fields to connected_accounts table for family matching
        Schema::table('connected_accounts', function (Blueprint $table) {
            $table->boolean('enable_family_matching')->default(false)->after('expires_at');
            $table->json('cached_profile_data')->nullable()->after('enable_family_matching');
            $table->timestamp('last_synced_at')->nullable()->after('cached_profile_data');
        });

        // Create social_connection_privacy table for granular privacy controls
        Schema::create('social_connection_privacy', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('allow_family_discovery')->default(true);
            $table->boolean('show_profile_to_matches')->default(true);
            $table->boolean('share_tree_with_matches')->default(false);
            $table->boolean('allow_contact_from_matches')->default(true);
            $table->json('blocked_users')->nullable();
            $table->timestamps();

            $table->unique('user_id');
        });

        // Create social_family_connections table for storing identified connections
        Schema::create('social_family_connections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('connected_account_id')->constrained()->onDelete('cascade');
            $table->string('matched_social_id');
            $table->string('matched_name')->nullable();
            $table->string('matched_email')->nullable();
            $table->string('relationship_type')->nullable(); // e.g., 'potential_relative', 'confirmed'
            $table->integer('confidence_score')->default(0); // 0-100
            $table->json('matching_criteria')->nullable(); // What matched (surname, location, etc.)
            $table->string('status')->default('pending'); // pending, accepted, rejected
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['connected_account_id', 'matched_social_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_family_connections');
        Schema::dropIfExists('social_connection_privacy');
        
        Schema::table('connected_accounts', function (Blueprint $table) {
            $table->dropColumn(['enable_family_matching', 'cached_profile_data', 'last_synced_at']);
        });
    }
};
