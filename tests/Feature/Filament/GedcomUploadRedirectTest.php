<?php

declare(strict_types=1);

namespace Tests\Feature\Filament;

use App\Filament\App\Resources\GedcomResource\Pages\CreateGedcom;
use App\Filament\App\Resources\ImportJobResource;
use App\Jobs\ImportGedcom;
use App\Jobs\ImportGrampsXml;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * The import should start — and the user should land on the Import Logs page —
 * the moment the file finishes uploading, without a "Create" click.
 */
final class GedcomUploadRedirectTest extends TestCase
{
    use RefreshDatabase;

    private function actAsTeamMember(): User
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);
        Filament::setTenant($user->currentTeam, isQuiet: true);

        return $user;
    }

    public function test_uploading_a_gedcom_queues_the_import_and_redirects_to_import_logs(): void
    {
        Storage::fake('private');
        Bus::fake();
        $user = $this->actAsTeamMember();

        Livewire::test(CreateGedcom::class)
            ->set('data.filename', [UploadedFile::fake()->create('tree.ged', 10)])
            ->assertRedirect(ImportJobResource::getUrl('index'));

        // Exactly one import per upload — the auto-submit must not re-fire.
        Bus::assertDispatchedTimes(ImportGedcom::class, 1);
        $this->assertDatabaseCount('importjobs', 1);
        $this->assertDatabaseHas('importjobs', ['user_id' => $user->id]);
    }

    public function test_uploading_a_gramps_file_dispatches_the_gramps_import(): void
    {
        Storage::fake('private');
        Bus::fake();
        $this->actAsTeamMember();

        Livewire::test(CreateGedcom::class)
            ->set('data.filename', [UploadedFile::fake()->create('tree.gramps', 10)])
            ->assertRedirect(ImportJobResource::getUrl('index'));

        Bus::assertDispatched(ImportGrampsXml::class);
    }
}
