<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportTemplatesTable extends Migration
{
    public function up()
    {
        Schema::create('import_templates', function (Blueprint $table) {
            $table->increments('id');

            $table->string('type')->index();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('import_templates');
    }
}
