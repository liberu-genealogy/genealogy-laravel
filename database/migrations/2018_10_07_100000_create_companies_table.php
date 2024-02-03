<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')->unique()->index();

            $table->string('reg_com_nr')->nullable();
            $table->string('fiscal_code')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('website')->nullable();

            $table->string('bank')->nullable();
            $table->string('bank_account')->nullable();

            $table->text('notes')->nullable();

            $table->boolean('pays_vat')->nullable();
            $table->tinyInteger('status');
            $table->boolean('is_tenant')->nullable();

            $table->integer('created_by')->unsigned()->index()->nullable();
     //       $table->foreign('created_by')->references('id')->on('users');

            $table->integer('updated_by')->unsigned()->index()->nullable();
       //     $table->foreign('updated_by')->references('id')->on('users');

            $table->timestamps();

            $table->unique('reg_com_nr');
            $table->unique('fiscal_code');
        });
    }

    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('companies');
        Schema::enableForeignKeyConstraints();
    }
}
