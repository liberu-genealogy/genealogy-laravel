<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDescriptionDefaultPlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (Schema::hasColumn('places', 'description')) {
            Schema::table('places', function (Blueprint $table) {
                $table->dropColumn('description');
            });
        }
        Schema::table('places', function (Blueprint $table) {
            $table->text('description')->nullable()->after('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('places', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
}
