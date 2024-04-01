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
        Schema::create('new_family_events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users');
            $table->text('date')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('converted_date')->nullable();
            $table->integer('year')->nullable();
            $table->integer('month')->nullable();
            $table->integer('day')->nullable();
            $table->string('type')->nullable();
            $table->string('plac')->nullable();
            $table->string('phon')->nullable();
            $table->text('caus')->nullable();
            $table->string('age')->nullable();
            $table->string('agnc')->nullable();
            $table->integer('husb')->nullable();
            $table->integer('wife')->nullable();
            $table->foreignId('addr_id')->constrained('addrs')->nullable();
            $table->integer('family_id')->nullable(); // unknown table
            $table->foreignId('places_id')->constrained('places')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('new_family_events');
    }
};
