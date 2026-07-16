<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSourceRefTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('source_ref', function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->string('group')->nullable();
            $table->integer('gid')->nullable();
            $table->integer('sour_id')->nullable();
            $table->string('text')->nullable();
            $table->string('quay')->nullable();
            $table->string('page')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('source_ref');
    }
}
