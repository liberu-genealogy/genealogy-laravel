<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFamilySlgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('family_slgs', function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->integer('family_id'); // unknown table
            $table->string('stat')->nullable();
            $table->string('date')->nullable();
            $table->string('plac')->nullable();
            $table->string('temp')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('family_slgs');
    }
}
