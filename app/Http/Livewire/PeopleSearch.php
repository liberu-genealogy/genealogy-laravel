<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Person;

class PeopleSearch extends Component
{
    public $query = '';
    public $results = [];

    protected $listeners = ['updatedQuery' => 'searchPeople'];

    public function mount()
    {
        $this->searchPeople();
    }

    public function searchPeople()
    {
        $this->results = Person::where('givn', 'like', '%' . $this->query . '%')
                               ->orWhere('surn', 'like', '%' . $this->query . '%')
                               ->get();
    }

    /**
    * Render the component view.
    *
    * This function is responsible for rendering the 'livewire.people-search' view and passing the search results to it.
    *
    * @return \Illuminate\Contracts\View\View The view instance.
    */
    public function render()
    {
        return view('livewire.people-search', [
            'results' => $this->results,
        ]);
    }
}
use Carbon\Carbon;
    public function render()
    {
        return view('livewire.people-search', [
            'results' => $this->results,
        ]);
    }
}
