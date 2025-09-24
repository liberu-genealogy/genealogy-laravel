<?php

namespace App\Http\Livewire;

use Illuminate\Contracts\View\View;
use App\Models\Person;
use App\Models\Family;
use Filament\Widgets\Widget;
use Illuminate\Support\Collection;

class DescendantChartWidget extends Widget
{
    protected string $view = 'filament.widgets.descendant-chart-widget';

    public $rootPersonId = null;
    public $generations = 4;
    public $showSpouses = true;
    public $showDates = true;
    public $showPhotos = false;
    public $layout = 'vertical'; // vertical, horizontal, tree
    public $colorScheme = 'generation';

    public function mount($rootPersonId = null, $generations = 4)
    {
        $this->rootPersonId = $rootPersonId ?? Person::first()?->id;
        $this->generations = $generations;
    }

    public function getData(): array
    {
        if (!$this->rootPersonId) {
            return ['descendantData' => [], 'rootPerson' => null];
        }

        $rootPerson = Person::with(['familiesAsHusband.wife', 'familiesAsWife.husband'])->find($this->rootPersonId);
        $descendantData = $this->buildDescendantTree($rootPerson, $this->generations);

        return [
            'descendantData' => $descendantData,
            'rootPerson' => $rootPerson,
            'generations' => $this->generations,
            'showSpouses' => $this->showSpouses,
            'showDates' => $this->showDates,
            'showPhotos' => $this->showPhotos,
            'layout' => $this->layout,
            'colorScheme' => $this->colorScheme,
        ];
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
            'birth_year' => $person->birthday?->format('Y'),
            'death_year' => $person->deathday?->format('Y'),
            'generation' => $generation,
            'families' => []
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
            $familyData = [
                'family_id' => $family->id,
                'spouse' => null,
                'children' => []
            ];

            // Add spouse information
            if ($this->showSpouses) {
                $spouse = null;
                if ($person->sex === 'M' && $family->wife) {
                    $spouse = $family->wife;
                } elseif ($person->sex === 'F' && $family->husband) {
                    $spouse = $family->husband;
                }

                if ($spouse) {
                    $familyData['spouse'] = [
                        'id' => $spouse->id,
                        'name' => $spouse->fullname(),
                        'givn' => $spouse->givn,
                        'surn' => $spouse->surn,
                        'sex' => $spouse->sex,
                        'birth_date' => $spouse->birthday?->format('Y-m-d'),
                        'death_date' => $spouse->deathday?->format('Y-m-d'),
                        'birth_year' => $spouse->birthday?->format('Y'),
                        'death_year' => $spouse->deathday?->format('Y'),
                    ];
                }
            }

            // Get children of this family
            $children = Person::where('child_in_family_id', $family->id)
                ->orderBy('birthday')
                ->get();

            foreach ($children as $child) {
                $childData = $this->buildDescendantTree($child, $maxGenerations, $generation + 1);
                if (!empty($childData)) {
                    $familyData['children'][] = $childData;
                }
            }

            if (!empty($familyData['children']) || $familyData['spouse']) {
                $personData['families'][] = $familyData;
            }
        }

        return $personData;
    }

    public function setRootPerson($personId): void
    {
        $this->rootPersonId = $personId;
        $this->dispatch('refreshDescendantChart');
    }

    public function setGenerations($generations): void
    {
        $this->generations = max(1, min(8, $generations));
        $this->dispatch('refreshDescendantChart');
    }

    public function toggleSpouses(): void
    {
        $this->showSpouses = !$this->showSpouses;
        $this->dispatch('refreshDescendantChart');
    }

    public function toggleDates(): void
    {
        $this->showDates = !$this->showDates;
        $this->dispatch('refreshDescendantChart');
    }

    public function togglePhotos(): void
    {
        $this->showPhotos = !$this->showPhotos;
        $this->dispatch('refreshDescendantChart');
    }

    public function setLayout($layout): void
    {
        $this->layout = $layout;
        $this->dispatch('refreshDescendantChart');
    }

    public function setColorScheme($scheme): void
    {
        $this->colorScheme = $scheme;
        $this->dispatch('refreshDescendantChart');
    }

    public function expandPerson($personId): void
    {
        $this->setRootPerson($personId);
    }

    public function render(): View
    {
        return view(static::$view, $this->getData());
    }
}