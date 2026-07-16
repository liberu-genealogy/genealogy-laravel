<?php

declare(strict_types=1);

namespace Tests\Filament\Resources;

use App\Filament\App\Resources\DnaResource\Pages\CreateDna;
use App\Models\Dna;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DnaConsentTest extends TestCase
{
    use RefreshDatabase;

    private function actingUser(): User
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);
        Filament::setTenant($user->currentTeam);

        return $user;
    }

    public function test_has_consent_reflects_the_flag(): void
    {
        $this->actingUser();

        $this->assertFalse(Dna::factory()->create(['consent_given' => false])->hasConsent());
        $this->assertTrue(Dna::factory()->create(['consent_given' => true])->hasConsent());
    }

    public function test_give_consent_sets_flag_and_timestamp(): void
    {
        $this->actingUser();

        $dna = Dna::factory()->create(['consent_given' => false]);
        $dna->giveConsent();

        $this->assertTrue($dna->hasConsent());
        $this->assertNotNull($dna->consent_given_at);
    }

    public function test_consented_scope_filters_to_consented_kits_only(): void
    {
        $this->actingUser();

        $consented = Dna::factory()->create(['consent_given' => true]);
        Dna::factory()->create(['consent_given' => false]);

        $ids = Dna::consented()->pluck('id');

        $this->assertTrue($ids->contains($consented->id));
        $this->assertCount(1, $ids);
    }

    public function test_create_page_mounts_with_consent_field(): void
    {
        $this->actingUser();

        Livewire::test(CreateDna::class)
            ->assertOk()
            ->assertFormFieldExists('consent_given');
    }
}
