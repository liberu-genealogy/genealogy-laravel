<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (Schema::hasColumn('person_events', 'attr')) {
            Schema::table('person_events', function (Blueprint $table) {
                $table->text('attr')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('person_events', function (Blueprint $table) {
            $table->text('attr')->nullable(false)->change();
        });
    }
};
