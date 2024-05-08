<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('new_repositories', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->string('group')->nullable();
            $table->integer('gid')->nullable();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->dateTime('date')->nullable();
            $table->integer('is_active')->nullable();
            $table->foreignId('type_id')->constrained('types')->nullable();
            $table->string('repo')->nullable();
            $table->foreignId('addr_id')->constrained('addrs')->nullable();
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
        Schema::dropIfExists('new_repositories');
    }
};
