<?php

namespace App\Http\Livewire;

use App\Models\Person;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\Computed;

final class DescendantChartComponent extends Component
{
    public Collection $descendantsData;

    public function mount(): void
    {
        $this->descendantsData = collect();
        $this->loadDescendants();
    }

    #[Computed]
    public function descendants(): Collection
    {
        return $this->descendantsData;
    }

    private function loadDescendants(): void
    {
        try {
            $this->descendantsData = Person::query()
                ->with('children')
                ->get()
                ->pipe(fn ($data) => $this->processDescendantData($data));
        } catch (\Throwable $e) {
            Log::error('Failed to load descendants', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $this->descendantsData = collect();
        }
    }

    private function processDescendantData(Collection $data): Collection
    {
        return $data->mapWithKeys(fn (Person $person) => [
            $person->id => [
                'id' => $person->id,
                'name' => $person->name,
                'children' => $person->children->pluck('id')->all()
            ]
        ])->filter(fn ($item) => empty($item['parent_id']));
    }

    public function render()
    {
        return view('livewire.descendant-chart-component');
    }
}