<?php

namespace App\Livewire;

use App\Actions\Jetstream\CreateTeam as CreateTeamAction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\Contracts\CreatesTeams;
use Laravel\Jetstream\Http\Livewire\CreateTeamForm;
use Override;

class CreateTeam extends CreateTeamForm
{
    /**
     * Create a new team.
     */
    #[Override]
    public function createTeam(CreatesTeams $creator): RedirectResponse
    {
        $this->validate();

        $team = app(CreateTeamAction::class)->create(
            Auth::user(),
            ['name' => $this->state['name']]
        );

        return redirect()->route('filament.app.tenant', ['tenant' => $team]);
    }
}
