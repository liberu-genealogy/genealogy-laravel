<?php

declare(strict_types=1);

namespace Tests\Feature\Tenancy;

use App\Filament\App\Pages\GedcomExportPage;
use App\Jobs\ExportGedCom;
use App\Jobs\ExportGrampsXml;
use App\Models\Family;
use App\Models\Person;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Exported family trees are the most concentrated data this application
 * produces — one file holding every person, date and relationship a team has
 * recorded — and they were the least protected thing in it.
 *
 * The export page listed every file on the shared private disk whose name ended
 * in the export suffix, with no filter by team or by user, and generated a
 * signed download URL for each. Any member of any team saw, and could download,
 * every other team's exported tree. The delete action was the same: a name
 * matching the expected pattern was sufficient, whoever it belonged to.
 *
 * Nothing about that required a role or an unusual request. It was the page
 * working as written.
 */
class GedcomExportIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_export_list_shows_only_your_own_teams_files(): void
    {
        Storage::fake('private');

        $mine = $this->actAsOwnerOfTeam();
        $ours = $this->exportFileFor($mine->currentTeam->id, '2026-01-01_010101_family_tree.ged');
        $theirs = $this->exportFileFor($this->otherTeamId(), '2026-02-02_020202_family_tree.ged');

        $listed = collect(Livewire::test(GedcomExportPage::class)->instance()->exportedFiles())
            ->pluck('name');

        $this->assertTrue($listed->contains(basename($ours)), 'A team could not see its own export.');
        $this->assertFalse(
            $listed->contains(basename($theirs)),
            'Another team\'s exported family tree was listed for download.',
        );
    }

    public function test_you_cannot_delete_another_teams_export(): void
    {
        Storage::fake('private');

        $this->actAsOwnerOfTeam();
        $theirs = $this->exportFileFor($this->otherTeamId(), '2026-02-02_020202_family_tree.ged');

        Livewire::test(GedcomExportPage::class)->call('deleteFile', basename($theirs));

        Storage::disk('private')->assertExists($theirs);
    }

    public function test_you_can_delete_your_own_export(): void
    {
        Storage::fake('private');

        $mine = $this->actAsOwnerOfTeam();
        $ours = $this->exportFileFor($mine->currentTeam->id, '2026-01-01_010101_family_tree.ged');

        Livewire::test(GedcomExportPage::class)->call('deleteFile', basename($ours));

        Storage::disk('private')->assertMissing($ours);
    }

    /**
     * The file's contents, not just who can see the file.
     *
     * The export runs as a queued job, and the tenant scope is applied by a
     * global scope that returns early when there is no authenticated user —
     * which is every job. So the generator was free to walk the whole table.
     */
    public function test_an_exported_tree_contains_only_its_own_teams_people(): void
    {
        Storage::fake('private');

        $owner = $this->actAsOwnerOfTeam();
        $ours = $this->treeFor($owner->current_team_id);
        $theirs = $this->treeFor($this->otherTeamId());

        $user = $owner->fresh();

        // A queue worker has no authenticated user and no tenant, and that is
        // the entire point: the tenant scope is a global scope that returns
        // early when nobody is authenticated. Leaving the test's own session in
        // place made this pass whether or not the job established anything —
        // it was the test that was scoping the query, not the code under test.
        Auth::logout();
        Filament::setTenant(null, isQuiet: true);

        (new ExportGedCom('export.ged', $user, $owner->current_team_id))->handle();

        $content = Storage::disk('private')->get($this->pathFor($owner->current_team_id, 'export.ged'));

        // Asserted on the HUSB pointer rather than a name or an INDI record.
        // Names live in person_name, not on the person, so a fixture setting
        // one proves nothing — and the generator emits no INDI records at all,
        // which makes the document invalid GEDCOM and is its own bug, filed
        // separately. The family pointers are emitted, and they carry the
        // person ids, which is enough to tell whose tree this is.
        //
        // Both directions, too. Asserting only the other team's absence passes
        // on an empty file — which is what an earlier version of this test did,
        // since people reach the export through families and the fixture had
        // none. 105 bytes of envelope satisfied it.
        $this->assertStringContainsString(
            "HUSB @{$ours}@",
            $content,
            'The export did not contain the team\'s own people, so it proves nothing about the rest.',
        );

        $this->assertStringNotContainsString(
            "HUSB @{$theirs}@",
            $content,
            'The exported tree contained another team\'s people.',
        );
    }

    /**
     * The GrampsXML export had the same two faults as the GEDCOM one and was
     * fixed alongside it, so it is pinned the same way. The leak was plainer
     * here: Person::all() is handed straight to the generator, and in a job
     * nothing is authenticated, so the tenant scope no-ops and returns every
     * team's people.
     *
     * No page lists these files, which is the only reason this was not also a
     * download of other teams' trees.
     */
    public function test_an_exported_gramps_tree_contains_only_its_own_teams_people(): void
    {
        Storage::fake('private');

        $owner = $this->actAsOwnerOfTeam();
        $ours = $this->treeFor($owner->current_team_id);
        $theirs = $this->treeFor($this->otherTeamId());

        $user = $owner->fresh();

        Auth::logout();
        Filament::setTenant(null, isQuiet: true);

        (new ExportGrampsXml('export.gramps', $user, $owner->current_team_id))->handle();

        $content = Storage::disk('private')->get($this->pathFor($owner->current_team_id, 'export.gramps'));

        // The person handle, not the bare id: a lone digit turns up in the
        // DTD version and the timestamps, so asserting on it would pass or fail
        // for reasons having nothing to do with whose tree this is.
        $this->assertStringContainsString(
            "person_{$ours}",
            $content,
            'The export did not contain the team\'s own people, so it proves nothing about the rest.',
        );

        $this->assertStringNotContainsString(
            "person_{$theirs}",
            $content,
            'The exported tree contained another team\'s people.',
        );
    }

    /**
     * A husband, a wife and the family joining them, since people reach the
     * export through families and a person alone is invisible to it.
     *
     * @return int the husband's id, as it appears in the exported document
     */
    private function treeFor(int $teamId): int
    {
        $husband = Person::factory()->create(['team_id' => $teamId]);
        $wife = Person::factory()->create(['team_id' => $teamId]);

        Family::factory()->create([
            'team_id' => $teamId,
            'husband_id' => $husband->id,
            'wife_id' => $wife->id,
        ]);

        return (int) $husband->id;
    }

    private function actAsOwnerOfTeam(): User
    {
        $owner = User::factory()->withPersonalTeam()->create();
        $this->actingAs($owner);
        Filament::setTenant($owner->currentTeam, isQuiet: true);

        return $owner;
    }

    private function otherTeamId(): int
    {
        return User::factory()->withPersonalTeam()->create()->current_team_id;
    }

    /**
     * Names must match the pattern the page accepts, or the test proves only
     * that an unrecognised filename is ignored.
     */
    private function exportFileFor(int $teamId, string $name): string
    {
        $path = $this->pathFor($teamId, $name);
        Storage::disk('private')->put($path, "0 HEAD\n0 TRLR\n");

        return $path;
    }

    private function pathFor(int $teamId, string $name): string
    {
        return "exports/{$teamId}/{$name}";
    }
}
