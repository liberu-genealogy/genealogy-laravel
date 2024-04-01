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
        Schema::create('new_people', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->onDelete('cascade');

            $table->string('givn');
            $table->string('surn');
            $table->enum('sex', ['M', 'F']);
            $table->unsignedBigInteger('child_in_family_id')->nullable();
            $table->text('description')->nullable();
            $table->string('titl')->nullable();
            $table->string('name')->nullable();
            $table->string('appellative')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->dateTime('birthday')->nullable();
            $table->dateTime('deathday')->nullable();
            $table->dateTime('burial_day')->nullable();
            $table->string('bank')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('chan')->nullable();
            $table->string('rin')->nullable();
            $table->string('resn')->nullable();
            $table->string('rfn')->nullable();
            $table->string('afn')->nullable();
            // $table->foreignId('child_in_family_id')->references('id')->on('families')->onDelete('set null');
            $table->timestamps();

            
       
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('new_people');
    }
};
