<?php

declare(strict_types=1);

namespace Tests\Feature\Filament;

use App\Filament\App\Resources\FamilyEventResource\Pages\CreateFamilyEvent;
use App\Models\Family;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * FamilyEventResource still referenced columns that no migration creates
 * (type/plac/phon/caus/age/agnc/husb/wife/addr_id). They are in the vendor
 * model's fillable, so the form wrote them on save — "table family_events has
 * no column named type". Guards that a create round-trips through the real
 * columns only. (year/month/day ARE real — added by a later migration — so
 * they stay; only the never-created columns were removed.)
 */
class FamilyEventResourceTest extends TestCase
{
    use RefreshDatabase;

    private function actAsTeamMember(): User
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);
        Filament::setTenant($user->currentTeam, isQuiet: true);

        return $user;
    }

    public function test_a_family_event_can_be_created(): void
    {
        $this->actAsTeamMember();
        $family = Family::factory()->create();

        Livewire::test(CreateFamilyEvent::class)
            ->fillForm([
                'family_id' => $family->id,
                'date' => '1900',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('family_events', [
            'family_id' => $family->id,
            'date' => '1900',
        ]);
    }
}
