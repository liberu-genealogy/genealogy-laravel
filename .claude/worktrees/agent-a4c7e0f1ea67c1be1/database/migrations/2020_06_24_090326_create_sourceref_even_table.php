<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSourcerefEvenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('sourceref_even', function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->string('group')->nullable();
            $table->integer('gid')->nullable();
            $table->string('even')->nullable();
            $table->string('role')->nullable();

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
        Schema::dropIfExists('sourceref_even');
    }
}
