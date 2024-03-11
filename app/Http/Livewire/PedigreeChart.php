<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Person;
use Illuminate\Support\Collection;

    /**
     * Class PedigreeChart
     *
     * Represents the PedigreeChart Livewire component.
     */
class PedigreeChart extends Component
{
    public Collection $people;

    /**
     * Mount function
     *
     * Initializes the component by fetching the list of people.
     */
    public function mount()
    {
        $this->people = Person::all(); // Simplified fetching logic for demonstration
    }

    /**
     * Render function
     *
     * Renders the pedigree-chart view.
     */
    public function render()
    {
        return view('livewire.pedigree-chart');
    }

    /**
     * Initialize Chart function
     *
     * Initializes the pedigree chart by dispatching a browser event to initialize the chart.
     */
    public function initializeChart()
    {
        $this->dispatchBrowserEvent('initializeChart', ['people' => $this->people->toJson()]);
    }

    /**
     * Zoom In function
     *
     * Handles the zoom in action by dispatching a browser event.
     */
    public function zoomIn()
    {
        $this->dispatchBrowserEvent('zoomIn');
    }

    /**
     * Zoom Out function
     *
     * Handles the zoom out action by dispatching a browser event.
     */
    public function zoomOut()
    {
        $this->dispatchBrowserEvent('zoomOut');
    }

    /**
     * Pan function
     *
     * Handles the pan action by dispatching a browser event with the provided direction.
     */
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
