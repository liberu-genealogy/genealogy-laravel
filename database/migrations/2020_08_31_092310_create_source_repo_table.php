<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSourceRepoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('source_repo', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('group');
            $table->integer('gid');
            $table->integer('repo_id'); // unknown table
            $table->text('caln');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('source_repo');
    }
}
