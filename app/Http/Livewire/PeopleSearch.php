<?php

namespace App\Http\Livewire;

use App\Models\Person;
use Livewire\Component;

class PeopleSearch extends Component
{
    public $query = '';
    public $results = [];

    protected $listeners = ['updatedQuery' => 'searchPeople'];

    public function mount(): void
    {
        $this->searchPeople();
    }

    public function searchPeople(): void
    {
        $this->results = Person::where('givn', 'like', '%'.$this->query.'%')
                               ->orWhere('surn', 'like', '%'.$this->query.'%')
                               ->get();
    }

    public function render()
    {
        return view('livewire.people-search', [
            'results' => $this->results,
        ]);
    }
}
