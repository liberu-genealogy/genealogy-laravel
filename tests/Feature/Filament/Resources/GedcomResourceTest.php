<?php

namespace Tests\Feature\Filament\Resources;

use App\Filament\App\Resources\GedcomResource;
use App\Filament\App\Resources\GedcomResource\Pages\CreateGedcom;
use App\Jobs\ExportGedCom;
use App\Jobs\ImportGedcom;
use App\Jobs\ImportGrampsXml;
use App\Models\Gedcom;
use App\Models\ImportJob;
use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GedcomResourceTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();
        Queue::fake();
        $this->user = User::factory()->withPersonalTeam()->create();
    }

    public function test_resource_has_correct_model(): void
    {
        $this->assertEquals(Gedcom::class, GedcomResource::getModel());
    }

    public function test_resource_navigation_is_configured(): void
    {
        $this->assertNotEmpty(GedcomResource::getNavigationLabel());
        $this->assertNotEmpty(GedcomResource::getNavigationIcon());
    }

    public function test_resource_has_pages_defined(): void
    {
        $pages = GedcomResource::getPages();
        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
    }

    public function test_can_create_returns_true_for_authenticated_user(): void
    {
        Auth::login($this->user);

        $this->assertTrue(GedcomResource::canCreate());
    }

    public function test_can_create_returns_false_when_unauthenticated(): void
    {
        Auth::logout();

        $this->assertFalse(GedcomResource::canCreate());
    }

    public function test_export_gedcom_dispatches_job_with_authenticated_user(): void
    {
        Auth::login($this->user);

        GedcomResource::exportGedcom();

        Queue::assertPushed(ExportGedCom::class, fn ($job): bool => $job->user->id === $this->user->id);
    }

    public function test_export_gedcom_does_not_dispatch_without_authenticated_user(): void
    {
        Auth::logout();

        GedcomResource::exportGedcom();

        Queue::assertNotPushed(ExportGedCom::class);
    }

    public function test_after_create_dispatches_import_gedcom_for_ged_file(): void
    {
        Auth::login($this->user);
        $disk = Storage::fake('private');
        $disk->put('gedcom-form-imports/test.ged', '0 HEAD');

        $gedcom = Gedcom::create(['filename' => 'gedcom-form-imports/test.ged']);

        $page = new CreateGedcom();
        $page->record = $gedcom;

        $method = new \ReflectionMethod($page, 'afterCreate');
        $method->invoke($page);

        Queue::assertPushed(ImportGedcom::class);
        Queue::assertNotPushed(ImportGrampsXml::class);
    }

    public function test_after_create_dispatches_gedcom_job_when_filename_is_array(): void
    {
        Auth::login($this->user);
        $disk = Storage::fake('private');
        $disk->put('gedcom-form-imports/test.ged', '0 HEAD');

        // Filament FileUpload may persist as an array even for single-file uploads;
        // CreateGedcom::afterCreate must extract the first element safely.
        $gedcom = Gedcom::create(['filename' => 'placeholder']);
        // Simulate Filament storing the path as an array in the model instance
        $gedcom->setAttribute('filename', ['gedcom-form-imports/test.ged']);

        $page = new CreateGedcom();
        $page->record = $gedcom;

        $method = new \ReflectionMethod($page, 'afterCreate');
        $method->invoke($page);

        Queue::assertPushed(ImportGedcom::class);
        Queue::assertNotPushed(ImportGrampsXml::class);
    }

    public function test_after_create_dispatches_import_gramps_xml_for_gramps_file(): void
    {
        Auth::login($this->user);
        $disk = Storage::fake('private');
        $disk->put('gedcom-form-imports/test.gramps', '<database/>');

        $gedcom = Gedcom::create(['filename' => 'gedcom-form-imports/test.gramps']);

        $page = new CreateGedcom();
        $page->record = $gedcom;

        $method = new \ReflectionMethod($page, 'afterCreate');
        $method->invoke($page);

        Queue::assertPushed(ImportGrampsXml::class);
        Queue::assertNotPushed(ImportGedcom::class);
    }

    public function test_after_create_does_not_dispatch_when_filename_is_empty(): void
    {
        Auth::login($this->user);
        Storage::fake('private');

        $gedcom = Gedcom::create(['filename' => '']);

        $page = new CreateGedcom();
        $page->record = $gedcom;

        $method = new \ReflectionMethod($page, 'afterCreate');
        $method->invoke($page);

        Queue::assertNotPushed(ImportGedcom::class);
        Queue::assertNotPushed(ImportGrampsXml::class);
    }

    public function test_after_create_does_not_dispatch_when_file_not_found_on_disk(): void
    {
        Auth::login($this->user);
        Storage::fake('private');
        // File is NOT placed in the fake disk; afterCreate should abort gracefully.

        $gedcom = Gedcom::create(['filename' => 'gedcom-form-imports/missing.ged']);

        $page = new CreateGedcom();
        $page->record = $gedcom;

        $method = new \ReflectionMethod($page, 'afterCreate');
        $method->invoke($page);

        Queue::assertNotPushed(ImportGedcom::class);
        Queue::assertNotPushed(ImportGrampsXml::class);
    }

    public function test_after_create_moves_file_from_livewire_tmp_and_dispatches_job(): void
    {
        Auth::login($this->user);
        $disk = Storage::fake('private');

        // Simulate a file that Livewire stored in its temporary directory instead of
        // the final gedcom-form-imports directory (e.g. when Filament's file-move step
        // did not run before afterCreate was called).
        $tmpPath = 'livewire-tmp/abcdef-test.ged';
        $disk->put($tmpPath, '0 HEAD');

        $gedcom = Gedcom::create(['filename' => $tmpPath]);

        $page = new CreateGedcom();
        $page->record = $gedcom;

        $method = new \ReflectionMethod($page, 'afterCreate');
        $method->invoke($page);

        // The file should have been moved out of livewire-tmp
        $disk->assertMissing($tmpPath);
        $disk->assertExists('gedcom-form-imports/abcdef-test.ged');

        // The Gedcom record should point to the new location
        $this->assertEquals('gedcom-form-imports/abcdef-test.ged', $gedcom->fresh()->filename);

        // The import job should still be dispatched
        Queue::assertPushed(ImportGedcom::class);
    }

    public function test_after_create_pre_creates_import_job_before_dispatch(): void
    {
        Auth::login($this->user);
        $disk = Storage::fake('private');
        $disk->put('gedcom-form-imports/test.ged', '0 HEAD');

        $gedcom = Gedcom::create(['filename' => 'gedcom-form-imports/test.ged']);

        $page = new CreateGedcom();
        $page->record = $gedcom;

        $method = new \ReflectionMethod($page, 'afterCreate');
        $method->invoke($page);

        // An ImportJob should be created in the database before the job runs
        $this->assertDatabaseHas('importjobs', [
            'user_id' => $this->user->id,
            'status'  => 'queue',
            'progress' => 0,
        ]);
    }

    public function test_after_create_dispatches_gedcom_job_with_slug(): void
    {
        Auth::login($this->user);
        $disk = Storage::fake('private');
        $disk->put('gedcom-form-imports/test.ged', '0 HEAD');

        $gedcom = Gedcom::create(['filename' => 'gedcom-form-imports/test.ged']);

        $page = new CreateGedcom();
        $page->record = $gedcom;

        $method = new \ReflectionMethod($page, 'afterCreate');
        $method->invoke($page);

        // The dispatched ImportGedcom job must carry a slug matching the ImportJob
        Queue::assertPushed(ImportGedcom::class, function (ImportGedcom $job): bool {
            $importJob = ImportJob::where('slug', $job->slug)->first();

            return $importJob !== null && $importJob->user_id === $this->user->id;
        });
    }

    public function test_file_upload_component_accepts_ged_files_via_mime_type_map(): void
    {
        $upload = FileUpload::make('filename')
            ->acceptedFileTypes(['.ged', '.gramps', 'text/plain', 'application/xml', 'text/xml'])
            ->mimeTypeMap(['ged' => 'text/plain', 'gramps' => 'application/xml']);

        $mimeTypeMap = $upload->getMimeTypeMap();

        $this->assertArrayHasKey('ged', $mimeTypeMap, '.ged extension should have a MIME type mapping');
        $this->assertEquals('text/plain', $mimeTypeMap['ged'], '.ged files should map to text/plain');
        $this->assertArrayHasKey('gramps', $mimeTypeMap, '.gramps extension should have a MIME type mapping');
        $this->assertEquals('application/xml', $mimeTypeMap['gramps'], '.gramps files should map to application/xml');
    }

    public function test_file_upload_component_accepted_file_types_includes_ged_extension(): void
    {
        $upload = FileUpload::make('filename')
            ->acceptedFileTypes(['.ged', '.gramps', 'text/plain', 'application/xml', 'text/xml'])
            ->mimeTypeMap(['ged' => 'text/plain', 'gramps' => 'application/xml']);

        $acceptedTypes = $upload->getAcceptedFileTypes();

        $this->assertContains('.ged', $acceptedTypes, 'FileUpload should accept .ged files');
        $this->assertContains('.gramps', $acceptedTypes, 'FileUpload should accept .gramps files');
        $this->assertContains('text/plain', $acceptedTypes, 'FileUpload should accept text/plain MIME type');
    }
}

