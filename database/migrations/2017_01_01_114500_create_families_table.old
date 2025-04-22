<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFamiliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('families')) {
            Schema::create('families', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->text('description')->nullable();
                $table->integer('is_active')->nullable();
                $table->foreignId('type_id')->constrained('types')->nullable();
                $table->foreignId('husband_id')->constrained('persons')->nullable();
                $table->foreignId('wife_id')->constrained('persons')->nullable();
                $table->string('chan')->nullable();
                $table->string('nchi')->nullable();
                $table->string('rin')->nullable();

                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('families');
    }
}
