<?php

namespace App\Livewire;

use App\Models\Person;
use Livewire\Component;
use Livewire\Attributes\On;

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
        if (empty($this->query)) {
            $this->results = Person::limit(10)->get()->toArray();
        } else {
            $this->results = Person::where('givn', 'like', '%'.$this->query.'%')
                                   ->orWhere('surn', 'like', '%'.$this->query.'%')
                                   ->limit(20)
                                   ->get()
                                   ->toArray();
        }
    }

    public function render()
    {
        return view('livewire.people-search', [
            'results' => $this->results,
        ]);
    }
}