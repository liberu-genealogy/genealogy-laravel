<?php

namespace App\Http\Livewire;

use App\Models\Person;
use Livewire\Component; // Assuming there's a Person model to fetch genealogical data

class FanChart extends Component
{
    public function render()
    {
        $people = Person::all(); // Fetch all people/person data. Adjust query as needed for performance or specific requirements.

        return view('livewire.fan-chart', ['people' => $people]);
    }
}
