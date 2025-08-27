<?php

namespace App\Livewire;

use App\Actions\Jetstream\CreateTeam;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\Http\Livewire\CreateTeamForm;

class CreateTeam extends CreateTeamForm
{
    /**
     * Create a new team.
     */
    #[\Override]
    public function createTeam(\Laravel\Jetstream\Contracts\CreatesTeams $creator): \Illuminate\Http\RedirectResponse
    {
        $this->validate();

        $team = app(CreateTeam::class)->create(
            Auth::user(),
            ['name' => $this->state['name']]
        );

        return redirect()->route('filament.app.tenant', ['tenant' => $team]);
    }
}