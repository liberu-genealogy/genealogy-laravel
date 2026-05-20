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
        Schema::create('virtual_event_attendees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('virtual_event_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('person_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable();
            $table->enum('rsvp_status', ['pending', 'accepted', 'declined', 'maybe'])->default('pending');
            $table->dateTime('rsvp_date')->nullable();
            $table->text('rsvp_notes')->nullable();
            $table->boolean('attended')->default(false);
            $table->dateTime('joined_at')->nullable();
            $table->dateTime('left_at')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->json('attendance_data')->nullable(); // Store platform-specific attendance data
            $table->boolean('is_host')->default(false);
            $table->boolean('is_moderator')->default(false);
            $table->string('invitation_token')->nullable();
            $table->dateTime('invitation_sent_at')->nullable();
            $table->timestamps();

            $table->unique(['virtual_event_id', 'user_id']);
            $table->unique(['virtual_event_id', 'person_id']);
            $table->index(['rsvp_status']);
            $table->index(['attended']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('virtual_event_attendees');
    }
};