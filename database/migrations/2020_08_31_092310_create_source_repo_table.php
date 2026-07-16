<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSourceRepoTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('source_repo', function (Blueprint $table): void {
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
     */
    public function down(): void
    {
        Schema::dropIfExists('source_repo');
    }
}
