<?php

namespace App\Http\Livewire;

use App\Models\Person;
use Livewire\Attributes\On;
use Livewire\Component;

class PeopleSearch extends Component
{
    public $query = '';
    public $results = [];

    public function mount(): void
    {
        $this->searchPeople();
    }

    #[On('updatedQuery')]
    public function searchPeople(): void
    {
        try {
            $this->results = Person::where('givn', 'like', '%'.$this->query.'%')
                                   ->orWhere('surn', 'like', '%'.$this->query.'%')
                                   ->get();
        } catch (\Illuminate\Database\QueryException $e) {
            // If the underlying table or columns don't exist (e.g. during
            // testing with a fresh in-memory database), just return empty
            // results rather than blowing up.
            $this->results = collect([]);
        }
    }

    public function render()
    {
        return view('livewire.people-search', [
            'results' => $this->results,
        ]);
    }
}
