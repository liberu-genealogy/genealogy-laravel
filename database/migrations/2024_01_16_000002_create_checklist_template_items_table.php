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
        Schema::create('checklist_template_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('checklist_template_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->string('category')->nullable();
            $table->boolean('is_required')->default(false);
            $table->integer('estimated_time')->nullable()->comment('Estimated time in minutes');
            $table->json('resources')->nullable()->comment('Links, documents, etc.');
            $table->json('tips')->nullable()->comment('Helpful tips and notes');
            $table->timestamps();

            $table->index(['checklist_template_id', 'order']);
            $table->index(['category']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checklist_template_items');
    }
};