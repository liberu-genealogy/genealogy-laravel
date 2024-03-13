<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Person;
use Illuminate\Support\Collection;

class PedigreeChart extends Component
{
    public Collection $people;

    public function mount($people)
    {
        $this->people = collect($people); // Adjusted to accept people data from the widget
    }

    public function render()
    {
        return view('livewire.pedigree-chart');
    }

    public function initializeChart()
    {
        $this->dispatchBrowserEvent('initializeChart', ['people' => $this->people->toJson()]);
    }

    public function zoomIn()
    {
        $this->dispatchBrowserEvent('zoomIn');
    }

    public function zoomOut()
    {
        $this->dispatchBrowserEvent('zoomOut');
    }

    public function pan($direction)
    {
        $this->dispatchBrowserEvent('pan', ['direction' => $direction]);
    }

    protected function getListeners()
    {
        return [
            'zoomIn' => 'zoomIn',
            'zoomOut' => 'zoomOut',
            'pan' => 'pan',
        ];
    }
}
}
}
