<?php

namespace App\Http\Livewire;

use App\Models\Person;
use Illuminate\Support\Facades\Log;
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
            $rawData = Person::with('children')->get()->toArray();
            $this->descendantsData = $this->processDescendantData($rawData);
        } catch (\Exception $e) {
            // Handle errors, such as logging or setting an error state
            Log::error('Failed to retrieve or process descendants data: '.$e->getMessage());
            $this->descendantsData = [];
        }
    }

    private function processDescendantData($data)
    {
        $tree = [];
        foreach ($data as $item) {
            if (!isset($tree[$item['id']])) {
                $tree[$item['id']] = [
                    'id'       => $item['id'],
                    'name'     => $item['name'],
                    'children' => [],
                ];
            }
            foreach ($item['children'] as $child) {
                $tree[$item['id']]['children'][] = $child['id'];
            }
        }

        return array_filter($tree, function ($item) {
            return !isset($item['parent_id']);
        });
    }

    public function render()
    {
        return view('livewire.descendant-chart-component');
    }
}
