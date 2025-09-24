<?php

namespace App\Http\Livewire;

use Override;
use Laravel\Jetstream\Contracts\CreatesTeams;
use App\Actions\Jetstream\CreateTeam;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\Http\Livewire\CreateTeamForm;

class CreateTeam extends CreateTeamForm
{
    /**
     * Create a new team.
     *
     * @return void
     */
    #[Override]
    public function createTeam(CreatesTeams $creator)
    {
        $this->validate();

        $team = app(CreateTeam::class)->create(
            Auth::user(),
            ['name' => $this->state['name']]
        );

        return redirect()->route('filament.pages.edit-team', ['team' => $team]);
    }
}