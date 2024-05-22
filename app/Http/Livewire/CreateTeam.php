<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\Http\Livewire\CreateTeamForm;

class CreateTeam extends CreateTeamForm
{
    /**
     * Create a new team.
     *
     * @return void
     */
    public function createTeam()
    {
        $this->validate();

        $user = Auth::user();

        $user->ownedTeams()->create([
            'name' => $this->state['name'],
            'personal_team' => false,
        ]);

        return redirect()->route('teams.show', ['team' => $user->currentTeam]);
    }
}