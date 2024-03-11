&lt;?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Person;

class DescendantChartComponent extends Component
{
    public $descendantsData = [];

    public function mount()
    {
        $rawData = Person::all()->toArray();
        $this->descendantsData = $this->processDescendantData($rawData);
    }

    private function processDescendantData($data)
    {
        // Assuming a structure transformation for D3.js
        // This is a placeholder for the actual data processing logic
        return array_map(function ($item) {
            return [
                'id' => $item['id'],
                'name' => $item['name'],
                // Additional processing as per D3.js requirements
            ];
        }, $data);
    }

    public function render()
    {
        return view('livewire.descendant-chart-component', ['descendantsData' => $this->descendantsData]);
    }
}
