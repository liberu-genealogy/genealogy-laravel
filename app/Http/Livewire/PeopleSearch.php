<?php

namespace App\Http\Livewire;

use App\Models\Person;
use Livewire\Component;
use Livewire\WithPagination;

class PeopleSearch extends Component
{
    use WithPagination;

    public $query = '';
    public $crossTenant = false;
    public $excludeLiving = true;

    protected $queryString = ['query', 'crossTenant', 'excludeLiving'];

    public function updatedQuery()
    {
        $this->resetPage();
    }

    public function searchPeople()
    {
        $query = $this->crossTenant ? Person::crossTenant() : Person::query();

        $query->where(function ($q) {
            $q->where('givn', 'like', '%' . $this->query . '%')
              ->orWhere('surn', 'like', '%' . $this->query . '%');
        });

        if ($this->excludeLiving) {
            $query->where('birthday', '<=', now()->subYears(100)->format('Y-m-d'));
        }

        return $query->paginate(10);
    }

    public function render()
    {
        return view('livewire.people-search', [
            'results' => $this->searchPeople(),
        ]);
    }
}
