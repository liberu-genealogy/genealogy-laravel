<?php

namespace App\Filament\Pages;

use App\Models\Team;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CreateTeam extends Page
{
    protected static string $view = 'filament.pages.create-team';

    public $name = '';

    public function mount(): void
    {
        abort_unless(Filament::auth()->user()->canCreateTeams(), 403);
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->label('Team Name')
                ->required()
                ->maxLength(255),
        ];
    }

    public function submit()
    {
        $this->validate();

        $team = Team::forceCreate([
            'user_id' => Filament::auth()->id(),
            'name' => $this->name,
            'personal_team' => false,
        ]);

        Filament::auth()->user()->teams()->attach($team, ['role' => 'admin']);
        Filament::auth()->user()->switchTeam($team);

        return redirect()->route('filament.pages.edit-team', ['team' => $team]);
    }

    protected function getBreadcrumbs(): array
    {
        return [
            url()->current() => 'Create Team',
        ];
    }
}