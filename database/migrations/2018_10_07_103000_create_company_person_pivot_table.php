<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyPersonPivotTable extends Migration
{
    public function up()
    {
        Schema::create('company_person', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('company_id')->constrained('companies');
            $table->foreignId('person_id')->constrained('persons');
            $table->string('position')->nullable();
            $table->boolean('is_main');
            $table->boolean('is_mandatary');

            $table->timestamps();
            // $table->foreign('company_id')->references('id')->on('companies')->onUpdate('cascade')->onDelete('cascade');
            // $table->foreign('person_id')->references('id')->on('people')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('company_person');
    }
}
