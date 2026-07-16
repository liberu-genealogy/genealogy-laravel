<?php

declare(strict_types=1);

namespace Tests\Filament\Resources;

use App\Models\User;
use App\Modules\Person\Filament\Resources\DuplicateMatchResource\Pages\ListDuplicateMatches;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Module Filament resources under app/Modules/* were never registered with any
 * panel, so they were dead code carrying v3-era class drift that only fires on
 * page mount (e.g. DuplicateMatchResource type-hinted the non-existent
 * Filament\Resources\Table, fataling when the table schema is built). This
 * mounts each newly-registered module resource's page so a regression back to a
 * dead namespace / removed method fails loudly.
 */
class ModuleResourceMountTest extends TestCase
{
    use RefreshDatabase;

    private function actingUser(): User
    {
        $user = User::factory()->withPersonalTeam()->create();
        $this->actingAs($user);
        Filament::setTenant($user->currentTeam);

        return $user;
    }

    public function test_duplicate_match_list_page_mounts(): void
    {
        $this->actingUser();

        Livewire::test(ListDuplicateMatches::class)->assertOk();
    }
}
