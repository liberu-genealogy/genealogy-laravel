<?php

namespace Tests\Feature\Filament\Resources;

use App\Filament\App\Resources\GedcomResource;
use App\Filament\App\Resources\GedcomResource\Pages\CreateGedcom;
use App\Filament\App\Resources\ImportJobResource;
use App\Jobs\ExportGedCom;
use App\Jobs\ImportGedcom;
use App\Jobs\ImportGrampsXml;
use App\Models\Gedcom;
use App\Models\ImportJob;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms\Components\FileUpload;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class GedcomResourceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A source header is what makes PHP report a GEDCOM as
     * text/vnd.familysearch.gedcom rather than application/x-gedcom, and every
     * real export has one.
     */
    private const GEDCOM = "0 HEAD\n1 SOUR PAF 2.2\n1 CHAR ANSEL\n0 TRLR\n";

    protected $user;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();
        Queue::fake();
        $this->user = User::factory()->withPersonalTeam()->create();
    }

    /**
     * The upload as a user performs it, rather than afterCreate in isolation.
     *
     * Every other test here reaches afterCreate by reflection on a bare page
     * object, which skips the form entirely — so all of them stayed green while
     * no upload worked at all. The form rejected the file before a record was
     * created, which is why nothing appeared in the table and the page never
     * redirected: the redirect follows the record.
     */
    #[DataProvider('genealogyFileProvider')]
    public function test_uploading_a_family_tree_file_creates_a_record(string $filename, string $contents): void
    {
        Storage::fake('private');
        Queue::fake();

        $this->actAsTeamMember();

        // The upload itself submits (afterStateUpdated -> create), no button click.
        Livewire::test(CreateGedcom::class)
            ->set('data.filename', [UploadedFile::fake()->createWithContent($filename, $contents)])
            ->assertHasNoFormErrors();

        $this->assertSame(1, Gedcom::count(), "Uploading {$filename} created no record.");
    }

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function genealogyFileProvider(): array
    {
        return [
            'gedcom' => ['tree.ged', self::GEDCOM],
            'gramps' => ['tree.gramps', '<?xml version="1.0"?><database/>'],
            'xml' => ['tree.xml', '<?xml version="1.0"?><database/>'],
        ];
    }

    /**
     * Why the upload is validated by extension and not by media type.
     *
     * This is the assertion that actually pins the bug, because it works on the
     * bytes the way a real upload does. The media type PHP reports for a GEDCOM
     * depends on what is in the file: a bare one comes back as
     * application/x-gedcom, while anything carrying a source header — which
     * every real export does — comes back as text/vnd.familysearch.gedcom. The
     * form accepted neither, so no genuine GEDCOM could be uploaded at all.
     *
     * Adding those two names to the list would have fixed the examples in front
     * of us and left the next exporter's output failing the same way. The
     * extension is what CreateGedcom already switches on to choose an import
     * job, so it is what gets validated.
     */
    public function test_a_real_gedcom_is_accepted_despite_its_reported_media_type(): void
    {
        $upload = $this->uploadOf('royal.ged', self::GEDCOM);

        $this->assertSame(
            'text/vnd.familysearch.gedcom',
            $upload->getMimeType(),
            'Fixture is degenerate: this content no longer reproduces the media type that broke uploads.',
        );

        $this->assertTrue(
            validator(['file' => $upload], ['file' => ['extensions:ged,gramps,xml']])->passes(),
            'A real GEDCOM was refused by the rule the form now uses.',
        );
    }

    public function test_a_file_of_an_unsupported_kind_is_still_refused(): void
    {
        $this->assertFalse(
            validator(
                ['file' => $this->uploadOf('notes.txt', "just some notes\n")],
                ['file' => ['extensions:ged,gramps,xml']],
            )->passes(),
            'Validating by extension must still refuse what is not a family tree file.',
        );
    }

    /**
     * The redirect the user noticed was missing. It follows from the record
     * being created rather than being an independent step, so it is asserted
     * rather than assumed.
     */
    public function test_a_successful_upload_redirects_to_the_import_log(): void
    {
        Storage::fake('private');
        Queue::fake();

        $this->actAsTeamMember();

        // Redirect follows the upload directly — no "Create" click.
        Livewire::test(CreateGedcom::class)
            ->set('data.filename', [UploadedFile::fake()->createWithContent('tree.ged', self::GEDCOM)])
            ->assertRedirect(ImportJobResource::getUrl('index'));
    }

    /**
     * A genuine upload, backed by a real file on disk, so getMimeType() runs
     * over the bytes as it does in production. UploadedFile::fake() guesses
     * from the extension instead, which is exactly the difference that hid this.
     */
    private function uploadOf(string $name, string $contents): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'upload');
        file_put_contents($path, $contents);

        return new UploadedFile($path, $name, null, null, true);
    }

    private function actAsTeamMember(): void
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);
        Filament::setTenant($user->currentTeam, isQuiet: true);
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

    /**
     * A team is required as well as a user. Exports are written into the
     * exporting team's directory and listed from there, so a job dispatched
     * without one would produce a file belonging to nobody — which is how every
     * team's exports came to sit in one shared directory.
     */
    public function test_export_gedcom_dispatches_job_with_the_team_being_viewed(): void
    {
        $user = User::factory()->withPersonalTeam()->create();
        Auth::login($user);
        Filament::setTenant($user->currentTeam, isQuiet: true);

        GedcomResource::exportGedcom();

        Queue::assertPushed(
            ExportGedCom::class,
            fn ($job): bool => $job->user->id === $user->id && $job->teamId === $user->current_team_id,
        );
    }

    public function test_export_gedcom_does_not_dispatch_without_a_team(): void
    {
        Auth::login($this->user);
        Filament::setTenant(null, isQuiet: true);

        GedcomResource::exportGedcom();

        Queue::assertNotPushed(ExportGedCom::class);
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

        $page = new CreateGedcom;
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

        $page = new CreateGedcom;
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
        $disk->put('gedcom-form-imports/test.gramps', '<gramps/>');

        $gedcom = Gedcom::create(['filename' => 'gedcom-form-imports/test.gramps']);

        $page = new CreateGedcom;
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

        $page = new CreateGedcom;
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

        $page = new CreateGedcom;
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

        $page = new CreateGedcom;
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

        $page = new CreateGedcom;
        $page->record = $gedcom;

        $method = new \ReflectionMethod($page, 'afterCreate');
        $method->invoke($page);

        // An ImportJob should be created in the database before the job runs
        $this->assertDatabaseHas('importjobs', [
            'user_id' => $this->user->id,
            'status' => 'queue',
            'progress' => 0,
        ]);
    }

    public function test_after_create_dispatches_gedcom_job_with_slug(): void
    {
        Auth::login($this->user);
        $disk = Storage::fake('private');
        $disk->put('gedcom-form-imports/test.ged', '0 HEAD');

        $gedcom = Gedcom::create(['filename' => 'gedcom-form-imports/test.ged']);

        $page = new CreateGedcom;
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
