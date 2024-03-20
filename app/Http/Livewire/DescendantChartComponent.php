<?php

namespace App\Http\Livewire;

use App\Models\Person;
use Livewire\Component;

class DescendantChartComponent extends Component
{
    public $descendantsData = [];

    /**
     * Mounts the component and retrieves the descendants data.
     */
    public function mount()
    {
        try {
            $rawData = Person::all()->toArray();
            $this->descendantsData = $this->processDescendantData($rawData);
        } catch (\Exception $e) {
            // Handle errors, such as logging or setting an error state
            \Log::error('Failed to retrieve or process descendants data: ' . $e->getMessage());
            $this->descendantsData = [];
        }
    }

    private function processDescendantData($data)
    {
        // Transforming data into a hierarchical structure for D3.js
        $hierarchy = [];
        foreach ($data as $person) {
            $hierarchy[] = [ // Mocking up a simplified hierarchical structure
                'id' => $person['id'],
                'name' => $person['name'],
                'children' => [] // Assuming children can be populated elsewhere
            ];
        }
        return $hierarchy;
    }

    public function render()
    {
        return view('livewire.descendant-chart-component', ['descendantsData' => $this->descendantsData]);
    }
}
