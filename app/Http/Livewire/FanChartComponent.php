<?php

namespace App\Http\Livewire;

use App\Models\Person;
use Livewire\Component;

class FanChartComponent extends Component
{
    public $people;

    public function getColumnSpan(): int|string|array
    {
        return 'full';
    }

    public function render()
    {
        $this->people = Person::all();

        return view('livewire.fan-chart-component', ['people' => $this->people]);
    }
}
