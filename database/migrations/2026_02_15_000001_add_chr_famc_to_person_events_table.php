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
        if (!Schema::hasColumn('person_events', 'chr_famc')) {
            Schema::table('person_events', function (Blueprint $table) {
                $table->string('chr_famc')->nullable()->after('birt_famc');
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
        if (Schema::hasColumn('person_events', 'chr_famc')) {
            Schema::table('person_events', function (Blueprint $table) {
                $table->dropColumn('chr_famc');
            });
        }
    }
};
