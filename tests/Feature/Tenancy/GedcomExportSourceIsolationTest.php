<?php

declare(strict_types=1);

namespace Tests\Feature\Tenancy;

use App\Jobs\ExportGedCom;
use App\Models\Family;
use App\Models\Person;
use App\Models\Source;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * The GEDCOM generator resolves Source through the container, so a
 * tenant-scoped Source binding keeps one team's export from embedding another
 * team's source records. Guards the container binding + the upstream generator
 * fix together; revert-sensitive to removing the Source bind in AppServiceProvider.
 */
class GedcomExportSourceIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_export_excludes_another_teams_sources(): void
    {
        Storage::fake('private');

        // Victim team owns a source with a distinctive marker.
        $victim = User::factory()->withPersonalTeam()->create();
        Source::factory()->create([
            'team_id' => $victim->current_team_id,
            'sour' => 'VICTIM-SECRET-SOURCE',
            'titl' => 'Victim private citation',
        ]);

        // Exporting team has its own tree (a family, so a FAM record is emitted)
        // and its own source.
        $exporter = User::factory()->withPersonalTeam()->create();
        $teamId = $exporter->current_team_id;
        $husband = Person::factory()->create(['team_id' => $teamId]);
        $wife = Person::factory()->create(['team_id' => $teamId]);
        Family::factory()->create(['team_id' => $teamId, 'husband_id' => $husband->id, 'wife_id' => $wife->id]);
        Source::factory()->create(['team_id' => $teamId, 'sour' => 'EXPORTER-OWN-SOURCE']);

        (new ExportGedCom('tree.ged', $exporter, $teamId))->handle();

        $content = (string) Storage::disk('private')->get(ExportGedCom::directoryFor($teamId).'/tree.ged');

        // Positive assertion first: the exporter's own source must be present, so
        // the absence check below can't pass vacuously on an over-filtered/empty file.
        $this->assertStringContainsString('EXPORTER-OWN-SOURCE', $content);
        $this->assertStringNotContainsString('VICTIM-SECRET-SOURCE', $content);
    }
}
