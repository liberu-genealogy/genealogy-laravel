<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepositoriesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('repositories', function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->string('group')->nullable();
            $table->integer('gid')->nullable();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->dateTime('date')->nullable();
            $table->integer('is_active')->nullable();
            $table->foreignId('type_id')->nullable()->constrained('types');
            $table->string('repo')->nullable();
            $table->foreignId('addr_id')->nullable()->constrained('addrs');
            $table->string('rin')->nullable();
            $table->string('phon')->nullable();
            $table->string('email')->nullable();
            $table->string('fax')->nullable();
            $table->string('www')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repositories');
    }
}
