<?php

namespace App\Livewire;

use Throwable;
use App\Models\Person;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Illuminate\Support\Collection;

final class DescendantChartComponent extends Component
{
    public array $descendantsData = [];
    public ?int $rootPersonId = null;
    public int $generations = 4;

    public function mount($rootPersonId = null): void
    {
        $this->rootPersonId = $rootPersonId ?? Person::first()?->id;

        try {
            if ($this->rootPersonId) {
                $rootPerson = Person::find($this->rootPersonId);
                $this->descendantsData = $this->buildDescendantTree($rootPerson, $this->generations);
            } else {
                $this->descendantsData = [];
            }
        } catch (Throwable $e) {
            Log::error('Failed to retrieve descendants data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $this->descendantsData = [];
        }
    }

    private function buildDescendantTree($person, $maxGenerations, $generation = 1): array
    {
        if (!$person || $generation > $maxGenerations) {
            return [];
        }

        $personData = [
            'id' => $person->id,
            'name' => $person->fullname(),
            'givn' => $person->givn,
            'surn' => $person->surn,
            'sex' => $person->sex,
            'birth_date' => $person->birthday?->format('Y-m-d'),
            'death_date' => $person->deathday?->format('Y-m-d'),
            'generation' => $generation,
            'children' => []
        ];

        // Get all families where this person is a parent
        $families = collect();
        if ($person->familiesAsHusband) {
            $families = $families->merge($person->familiesAsHusband);
        }
        if ($person->familiesAsWife) {
            $families = $families->merge($person->familiesAsWife);
        }

        foreach ($families as $family) {
            $children = Person::where('child_in_family_id', $family->id)
                ->orderBy('birthday')
                ->get();

            foreach ($children as $child) {
                $childData = $this->buildDescendantTree($child, $maxGenerations, $generation + 1);
                if (!empty($childData)) {
                    $personData['children'][] = $childData;
                }
            }
        }

        return $personData;
    }

    public function setRootPerson(int $personId): void
    {
        $this->rootPersonId = $personId;
        $this->mount($personId);
        $this->dispatch('descendant-chart-updated');
    }

    public function setGenerations(int $generations): void
    {
        $this->generations = max(1, min(10, $generations));
        $this->mount($this->rootPersonId);
        $this->dispatch('descendant-chart-updated');
    }

    public function render()
    {
        return view('livewire.descendant-chart-component');
    }
}