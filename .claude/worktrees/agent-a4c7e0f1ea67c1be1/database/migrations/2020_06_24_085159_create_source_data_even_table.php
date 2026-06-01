<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSourceDataEvenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('source_data_even', function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->string('group')->nullable();
            $table->string('gid')->nullable();
            $table->string('date')->nullable();
            $table->string('plac')->nullable();

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
        Schema::dropIfExists('source_data_even');
    }
}
