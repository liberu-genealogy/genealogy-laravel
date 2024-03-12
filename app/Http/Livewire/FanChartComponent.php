<?php

/**
 * Livewire component for displaying the fan chart.
 */

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Person;

class FanChartComponent extends Component
{
    public $people;

    public function render()
    {
        $this->people = Person::all();
        return view('livewire.fan-chart-component', ['people' => $this->people]);
    }
}
     */
    public function render()
    {
        $this->people = Person::all();
        return view('livewire.fan-chart-component', ['people' => $this->people]);
    }
}
