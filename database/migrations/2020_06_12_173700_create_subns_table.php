<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubnsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subns', function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->string('subm')->nullable();
            $table->string('famf')->nullable();
            $table->string('temp')->nullable();
            $table->string('ance')->nullable();
            $table->string('desc')->nullable();
            $table->string('ordi')->nullable();
            $table->string('rin')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subns');
    }
}
