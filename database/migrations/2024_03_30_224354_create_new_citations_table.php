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
        Schema::create('new_citations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description');
            $table->dateTime('date')->nullable();
            $table->integer('is_active');
            $table->integer('volume');
            $table->integer('page');
            $table->integer('confidence');
            $table->integer('source_id'); // unknown table

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('new_citations');
    }
};
