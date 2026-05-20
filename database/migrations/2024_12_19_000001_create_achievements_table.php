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
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // e.g., 'first_person_added', 'genealogy_researcher'
            $table->string('name'); // Display name
            $table->text('description');
            $table->string('icon')->nullable(); // Icon class or path
            $table->string('category')->default('general'); // general, research, social, milestone
            $table->integer('points')->default(0); // Points awarded for this achievement
            $table->json('requirements')->nullable(); // JSON with requirements like {"count": 10, "type": "person"}
            $table->string('badge_color')->default('blue'); // Color for the badge
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};