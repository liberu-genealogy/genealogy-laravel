<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Livewire\AhnentafelReport;
use App\Models\Person;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AhnentafelReportTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->withPersonalTeam()->create();
        $team = $this->user->ownedTeams()->first();
        if ($team) {
            $this->user->forceFill(['current_team_id' => $team->id])->save();
        }
        $this->actingAs($this->user);
    }

    public function test_component_renders(): void
    {
        Livewire::test(AhnentafelReport::class)->assertOk();
    }

    public function test_initial_state_has_no_report_data(): void
    {
        Livewire::test(AhnentafelReport::class)
            ->assertSet('selectedPersonId', null)
            ->assertSet('reportData', []);
    }

    public function test_generate_report_with_valid_person(): void
    {
        $person = Person::factory()->create();

        Livewire::test(AhnentafelReport::class)
            ->call('generateReport', $person->id)
            ->assertSet('selectedPersonId', $person->id);
    }

    public function test_generate_report_with_nonexistent_person(): void
    {
        Livewire::test(AhnentafelReport::class)
            ->call('generateReport', 99999)
            ->assertSet('reportData', []);
    }

    public function test_report_carries_the_gedcom_birth_and_death_place(): void
    {
        // The report read $person->birth_place / ->death_place, which are not
        // columns (GEDCOM import writes birthday_plac / deathday_plac), so the
        // place always came back empty.
        $person = Person::factory()->create([
            'birthday_plac' => 'Manchester, England',
            'deathday_plac' => 'Leeds, England',
        ]);

        $data = Livewire::test(AhnentafelReport::class)
            ->call('generateReport', $person->id)
            ->get('reportData');

        $this->assertSame('Manchester, England', $data[1]['birth_place']);
        $this->assertSame('Leeds, England', $data[1]['death_place']);
    }
}
