<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyPersonPivotTable extends Migration
{
    public function up()
    {
        Schema::create('company_person', function (Blueprint $table) {
            $table->integer('company_id')->unsigned()->index();
//            $table->foreign('company_id')->references('id')->on('companies')
//                ->onUpdate('cascade')->onDelete('cascade');

            $table->integer('person_id')->unsigned()->index();
//            $table->foreign('person_id')->references('id')->on('people')
 //               ->onUpdate('cascade')->onDelete('cascade');

            $table->string('position')->nullable();

            $table->boolean('is_main');
            $table->boolean('is_mandatary');

            $table->primary(['company_id', 'person_id']);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('company_person');
        Schema::enableForeignKeyConstraints();
    }
}
