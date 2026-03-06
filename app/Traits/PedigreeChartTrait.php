<?php

namespace App\Traits;

use App\Models\Person;

trait PedigreeChartTrait
{
    public ?int $rootPersonId = null;
    public int $generations = 4;
    public bool $showDates = true;
    public bool $showPhotos = false;

    public function mount($rootPersonId = null, $generations = 4)
    {
        $this->rootPersonId = $rootPersonId ?? Person::first()?->id;
        $this->generations = $generations;
    }

    public function getData(): array
    {
        if (!$this->rootPersonId) {
            return ['tree' => [], 'rootPerson' => null];
        }

        $rootPerson = Person::with(['childInFamily.husband', 'childInFamily.wife'])->find($this->rootPersonId);
        $tree = $this->buildPedigreeTree($rootPerson, $this->generations);

        return [
            'tree' => $tree,
            'rootPerson' => $rootPerson,
            'generations' => $this->generations,
            'showDates' => $this->showDates,
            'showPhotos' => $this->showPhotos,
        ];
    }

    private function buildPedigreeTree($person, $generations, $level = 0): array
    {
        if (!$person || $level >= $generations) {
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
            'level' => $level,
            'position' => pow(2, $level),
            'parents' => []
        ];

        if ($person->childInFamily) {
            $family = $person->childInFamily;

            if ($family->husband) {
                $personData['parents']['father'] = $this->buildPedigreeTree($family->husband, $generations, $level + 1);
            }

            if ($family->wife) {
                $personData['parents']['mother'] = $this->buildPedigreeTree($family->wife, $generations, $level + 1);
            }
        }

        return $personData;
    }

    public function setRootPerson($personId): void
    {
        $this->rootPersonId = $personId;
        $this->emit('refreshChart');
    }

    public function setGenerations($generations): void
    {
        $this->generations = max(1, min(6, $generations));
        $this->emit('refreshChart');
    }

    public function toggleDates(): void
    {
        $this->showDates = !$this->showDates;
        $this->emit('refreshChart');
    }

    public function togglePhotos(): void
    {
        $this->showPhotos = !$this->showPhotos;
        $this->emit('refreshChart');
    }

    public function expandPerson($personId): void
    {
        $this->setRootPerson($personId);
    }
}
