<?php

namespace App\Livewire;

use App\Services\PersonSearchService;
use Livewire\Attributes\On;
use Livewire\Component;

class PeopleSearch extends Component
{
    public string $query = '';

    public array $results = [];

    public function mount(): void
    {
        $this->searchPeople();
    }

    public function updatedQuery(): void
    {
        $this->searchPeople();
    }

    #[On('updatedQuery')]
    public function searchPeople(): void
    {
        $service = app(PersonSearchService::class);

        if ($this->query === '' || $this->query === '0') {
            $this->results = $service->searchOwnTeam('', 10)->items();
        } else {
            $this->results = $service->searchOwnTeam($this->query, 20)->items();
        }

        $this->results = array_map(fn ($p) => is_object($p) ? $p->toArray() : $p, $this->results);
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.people-search', [
            'results' => $this->results,
        ]);
    }
}
