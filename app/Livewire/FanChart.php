<?php

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use App\Models\Person;
use Filament\Widgets\Widget;

class FanChart extends Widget
{
    protected static string $view = 'filament.widgets.fan-chart-widget';

    public ?int $rootPersonId = null;
    public int $generations = 5;
    public bool $showNames = true;
    public bool $showDates = false;
    public string $colorScheme = 'generation';

    public function mount($rootPersonId = null, $generations = 5): void
    {
        $this->rootPersonId = $rootPersonId ?? Person::first()?->id;
        $this->generations = $generations;
    }

    public function getData(): array
    {
        if (!$this->rootPersonId) {
            return ['fanData' => [], 'rootPerson' => null];
        }

        $rootPerson = Person::with(['childInFamily.husband', 'childInFamily.wife'])->find($this->rootPersonId);
        $fanData = $this->buildFanData($rootPerson, $this->generations);

        return [
            'fanData' => $fanData,
            'rootPerson' => $rootPerson,
            'generations' => $this->generations,
            'showNames' => $this->showNames,
            'showDates' => $this->showDates,
            'colorScheme' => $this->colorScheme,
        ];
    }

    private function buildFanData($person, $maxGenerations, $generation = 0): array
    {
        if (!$person || $generation >= $maxGenerations) {
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
            'children' => []
        ];

        // For fan chart, we build ancestors (parents) not descendants
        if ($person->childInFamily && $generation < $maxGenerations - 1) {
            $family = $person->childInFamily;

            if ($family->husband) {
                $personData['children'][] = $this->buildFanData($family->husband, $maxGenerations, $generation + 1);
            }

            if ($family->wife) {
                $personData['children'][] = $this->buildFanData($family->wife, $maxGenerations, $generation + 1);
            }
        }

        return $personData;
    }

    public function setRootPerson($personId): void
    {
        $this->rootPersonId = $personId;
        $this->dispatch('refreshFanChart');
    }

    public function setGenerations($generations): void
    {
        $this->generations = max(2, min(8, $generations));
        $this->dispatch('refreshFanChart');
    }

    public function toggleNames(): void
    {
        $this->showNames = !$this->showNames;
        $this->dispatch('refreshFanChart');
    }

    public function toggleDates(): void
    {
        $this->showDates = !$this->showDates;
        $this->dispatch('refreshFanChart');
    }

    public function setColorScheme($scheme): void
    {
        $this->colorScheme = $scheme;
        $this->dispatch('refreshFanChart');
    }

    public function render(): View
    {
        return view(static::$view, $this->getData());
    }
}
