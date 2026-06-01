<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportTemplatesTable extends Migration
{
    public function up(): void
    {
        Schema::create('import_templates', function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->string('type')->index();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('import_templates');
    }
}
