<?php

declare(strict_types=1);

namespace Tests\Feature\Filament;

use App\Jobs\ExportGedCom;
use App\Models\Family;
use App\Models\Person;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * The export job routes each format to the matching GedcomService writer and
 * stores it under the caller-chosen extension (.ged for 5.5.1/7, .json for X).
 */
class GedcomExportFormatTest extends TestCase
{
    use RefreshDatabase;

    private function exportAs(string $file, string $format): string
    {
        $user = User::factory()->withPersonalTeam()->create();
        $teamId = (int) $user->current_team_id;

        $husband = Person::factory()->create(['team_id' => $teamId]);
        $wife = Person::factory()->create(['team_id' => $teamId]);
        Family::factory()->create(['team_id' => $teamId, 'husband_id' => $husband->id, 'wife_id' => $wife->id]);

        (new ExportGedCom($file, $user, $teamId, $format))->handle();

        return (string) Storage::disk('private')->get(ExportGedCom::directoryFor($teamId).'/'.$file);
    }

    public function test_exports_gedcom_551(): void
    {
        Storage::fake('private');

        $content = $this->exportAs('tree.ged', '5.5.1');

        $this->assertStringContainsString('2 VERS 5.5.1', $content);
    }

    public function test_exports_gedcom_7(): void
    {
        Storage::fake('private');

        $content = $this->exportAs('tree.ged', '7.0');

        $this->assertStringContainsString('2 VERS 7.0', $content);
        $this->assertStringNotContainsString('1 CHAR', $content);
    }

    public function test_exports_gedcom_x_as_json(): void
    {
        Storage::fake('private');

        $content = $this->exportAs('tree.json', 'gedcomx');

        $decoded = json_decode($content, true);
        $this->assertIsArray($decoded);
        $this->assertArrayHasKey('persons', $decoded);
    }

    public function test_default_format_is_gedcom_551(): void
    {
        Storage::fake('private');

        $user = User::factory()->withPersonalTeam()->create();
        $teamId = (int) $user->current_team_id;
        Person::factory()->create(['team_id' => $teamId]);

        // No format argument — existing callers must still get 5.5.1.
        (new ExportGedCom('tree.ged', $user, $teamId))->handle();

        $content = (string) Storage::disk('private')->get(ExportGedCom::directoryFor($teamId).'/tree.ged');

        $this->assertStringContainsString('2 VERS 5.5.1', $content);
    }
}
