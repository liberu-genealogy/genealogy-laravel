<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('sources', function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('date')->nullable();
            $table->integer('is_active')->nullable();
            $table->foreignId('author_id')->nullable()->constrained('authors');
            $table->foreignId('repository_id')->nullable()->constrained('repositories');
            $table->foreignId('publication_id')->nullable()->constrained('publications');
            $table->foreignId('type_id')->nullable()->constrained('types');
            $table->string('sour')->nullable();
            $table->text('titl')->nullable();
            $table->string('auth')->nullable();
            $table->string('data')->nullable();
            $table->text('text')->nullable();
            $table->text('publ')->nullable();
            $table->string('abbr')->nullable();
            $table->string('group')->nullable();
            $table->integer('gid')->nullable();
            $table->string('quay')->nullable();
            $table->text('page')->nullable();
            $table->string('rin')->nullable();
            $table->string('note')->nullable();

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
        Schema::dropIfExists('sources');
    }
}
