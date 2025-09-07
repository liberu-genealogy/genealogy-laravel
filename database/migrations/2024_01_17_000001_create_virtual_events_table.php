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
        Schema::create('virtual_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('timezone')->default('UTC');
            $table->enum('status', ['draft', 'published', 'started', 'ended', 'cancelled'])->default('draft');
            $table->enum('platform', ['zoom', 'google_meet', 'teams', 'custom'])->default('zoom');
            $table->string('meeting_id')->nullable();
            $table->string('meeting_password')->nullable();
            $table->text('meeting_url')->nullable();
            $table->text('join_url')->nullable();
            $table->json('platform_data')->nullable(); // Store platform-specific data
            $table->integer('max_attendees')->nullable();
            $table->boolean('require_rsvp')->default(true);
            $table->boolean('allow_guests')->default(false);
            $table->text('instructions')->nullable();
            $table->string('host_email')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['team_id', 'status']);
            $table->index(['start_time', 'end_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('virtual_events');
    }
};