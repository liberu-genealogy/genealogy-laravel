<?php

namespace App\Http\Livewire;

use App\Models\Person;
use Livewire\Component;
use Carbon\Carbon;

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
        $hundredYearsAgo = Carbon::now()->subYears(100)->toDateString();

        $this->results = Person::where(function ($query) {
                                $query->where('givn', 'like', '%'.$this->query.'%')
                                      ->orWhere('surn', 'like', '%'.$this->query.'%');
                            })
                            ->where('birthday', '<=', $hundredYearsAgo)
                            ->withoutGlobalScope('team')
                            ->get();
    }

    public function render()
    {
        return view('livewire.people-search', [
            'results' => $this->results,
        ]);
    }
}
