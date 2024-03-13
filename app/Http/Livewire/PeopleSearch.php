<?php

/**
 * Livewire component for searching through people entities.
 * Allows users to input a search query and displays matching results from the people entities.
 */

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
        ]);
    }
}
            'results' => $this->results,
        ]);
    }
}
            'results' => $this->results,
        ]);
    }
}
