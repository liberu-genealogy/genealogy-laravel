<?php

namespace Tests\Feature\Filament\Resources;

use App\Filament\Resources\GedcomResource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class GedcomResourceTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_form_schema_contains_correct_fields_and_validations(): void
    {
        $form = GedcomResource::form(Livewire::mock());
        $schema = collect($form->getSchema());

        $fileUpload = $schema->firstWhere('name', 'attachment');
        $this->assertNotNull($fileUpload);
        $this->assertEquals('private', $fileUpload->getVisibility());
        $this->assertEquals(100000, $fileUpload->getMaxSize());
        $this->assertEquals('gedcom-form-imports', $fileUpload->getDirectory());
        $this->assertTrue($fileUpload->isRequired());
    }

    public function test_table_configuration(): void
    {
        $table = GedcomResource::table(Livewire::mock());
        $this->assertCount(0, $table->getColumns());
        $this->assertCount(0, $table->getFilters());

        $actions = $table->getActions();
        $this->assertNotEmpty($actions);
        $this->assertArrayHasKey('export', $actions);
    }

    public function test_file_upload_dispatches_import_gedcom_job(): void
    {
        Storage::fake('private');
        $file = UploadedFile::fake()->create('document.ged', 500);

        Livewire::actingAs($this->user)
            ->test(GedcomResource::class)
            ->set('attachment', $file)
            ->call('save');

        Storage::disk('private')->assertExists('gedcom-form-imports/'.$file->hashName());
        $this->assertDatabaseHas('jobs', ['queue' => 'default']);
    }
}
