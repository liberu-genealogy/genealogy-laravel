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
        Schema::create('user_checklist_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_checklist_id')->constrained()->onDelete('cascade');
            $table->foreignId('checklist_template_item_id')->nullable()->constrained()->onDelete('set null');
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->integer('estimated_time')->nullable()->comment('Estimated time in minutes');
            $table->integer('actual_time')->nullable()->comment('Actual time spent in minutes');
            $table->json('resources')->nullable()->comment('Links, documents, etc.');
            $table->json('tips')->nullable()->comment('Helpful tips and notes');
            $table->timestamps();

            $table->index(['user_checklist_id', 'order']);
            $table->index(['user_checklist_id', 'is_completed']);
            $table->index(['is_completed', 'completed_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_checklist_items');
    }
};