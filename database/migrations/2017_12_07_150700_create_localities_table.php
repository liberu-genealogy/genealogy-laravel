<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocalitiesTable extends Migration
{
    public function up()
    {
        Schema::create('localities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('region_id')->constrained('regions');
            $table->string('township')->nullable();
            $table->string('name');
            $table->string('siruta')->nullable();
            $table->float('lat', 10)->nullable();
            $table->float('long', 10)->nullable();
            $table->boolean('is_active');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('localities');
    }
}
