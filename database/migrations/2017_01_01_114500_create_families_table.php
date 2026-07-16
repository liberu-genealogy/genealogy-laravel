<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFamiliesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('families')) {
            Schema::create('families', function (Blueprint $table): void {
                $table->bigIncrements('id');
                $table->text('description')->nullable();
                $table->integer('is_active')->nullable();
                $table->foreignId('type_id')->constrained('types')->nullable();
                $table->foreignId('husband_id')->constrained('people')->nullable();
                $table->foreignId('wife_id')->constrained('people')->nullable();
                $table->string('chan')->nullable();
                $table->string('nchi')->nullable();
                $table->string('rin')->nullable();

                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('families');
    }
}
