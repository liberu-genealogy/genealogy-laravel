<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaObjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('media_objects', function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->integer('gid')->nullable();
            $table->string('group')->nullable();
            $table->string('titl')->nullable();
            // $table->string('rin')->nullable(); // duplicate??
            $table->string('obje_id')->nullable(); // don't know what type must be

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
        Schema::dropIfExists('media_objects');
    }
}
