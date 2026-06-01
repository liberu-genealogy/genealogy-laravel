<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (!Schema::hasTable('addrs')) {
            Schema::create('addrs', function (Blueprint $table): void {
                $table->bigIncrements('id');
                $table->string('adr1')->nullable();
                $table->string('adr2')->nullable();
                $table->string('city')->nullable();
                $table->string('stae')->nullable();
                $table->string('post')->nullable();
                $table->string('ctry')->nullable();

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('addrs');
    }
}
