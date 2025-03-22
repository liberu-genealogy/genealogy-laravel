<?php

namespace App\Http\Livewire;

use App\Models\Person;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Illuminate\Support\Collection;

final class DescendantChartComponent extends Component
{
    public array $descendantsData = [];

    public function mount(): void
    {
        try {
            $rawData = Person::with('children')->get();
            $this->descendantsData = $this->processDescendantData($rawData);
        } catch (\Throwable $e) {
            Log::error('Failed to retrieve descendants data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $this->descendantsData = [];
        }
    }

    private function processDescendantData(Collection $data): array
    {
        return $data->mapWithKeys(fn (Person $person) => [
            $person->id => [
                'id' => $person->id,
                'name' => $person->name,
                'children' => $person->children->pluck('id')->toArray()
            ]
        ])
        ->filter(fn ($item) => !isset($item['parent_id']))
        ->toArray();
    }

    public function render()
    {
        return view('livewire.descendant-chart-component');
    }
}