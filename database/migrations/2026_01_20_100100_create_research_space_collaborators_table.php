<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('research_space_collaborators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('research_space_id')->constrained('research_spaces')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('role')->default('editor'); // owner, admin, editor, viewer
            $table->json('permissions')->nullable();
            $table->foreignId('invited_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();

            $table->unique(['research_space_id', 'user_id']);
            $table->index(['user_id', 'research_space_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('research_space_collaborators');
    }
};
