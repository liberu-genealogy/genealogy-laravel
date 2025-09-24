<?php

namespace App\Livewire;

use Override;
use Laravel\Jetstream\Contracts\CreatesTeams;
use Illuminate\Http\RedirectResponse;
use App\Actions\Jetstream\CreateTeam;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\Http\Livewire\CreateTeamForm;

class CreateTeam extends CreateTeamForm
{
    /**
     * Create a new team.
     */
    #[Override]
    public function createTeam(CreatesTeams $creator): RedirectResponse
    {
        $this->validate();

        $team = app(CreateTeam::class)->create(
            Auth::user(),
            ['name' => $this->state['name']]
        );

        return redirect()->route('filament.app.tenant', ['tenant' => $team]);
    }
}