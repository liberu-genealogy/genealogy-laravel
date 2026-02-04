<?php

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use App\Models\Person;
use Filament\Widgets\Widget;
use Livewire\Attributes\On;

class PedigreeChartWidget extends Widget
{
    protected string $view = 'filament.widgets.pedigree-chart-widget';

    public ?int $rootPersonId = null;
    public int $generations = 4;
    public bool $showDates = true;
    public bool $showPhotos = false;

    public function mount($rootPersonId = null, $generations = 4): void
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

        // Get parents through family relationship
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

    public function render(): View
    {
        return view(static::$view, $this->getData());
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

    public function renderPedigreeTree($tree, $level = 0): string
    {
        if (empty($tree)) {
            return '';
        }

        $html = '<div class="generation-level level-' . $level . '">';

        // Add connection line if not root level
        if ($level > 0) {
            $html .= '<div class="connection-line"></div>';
        }

        // Person box
        $sexClass = strtolower($tree['sex'] ?? 'unknown');
        $html .= '<div class="person-box ' . $sexClass . '" onclick="expandPerson(' . $tree['id'] . ')">';
        $html .= '<button class="expand-btn" title="Expand from this person">↑</button>';
        $html .= '<div class="person-name">' . htmlspecialchars($tree['name']) . '</div>';

        if ($this->showDates) {
            $birthDate = $tree['birth_date'] ? date('Y', strtotime($tree['birth_date'])) : '?';
            $deathDate = $tree['death_date'] ? date('Y', strtotime($tree['death_date'])) : '';
            $dateRange = $birthDate . ($deathDate ? ' - ' . $deathDate : ' - ');
            $html .= '<div class="person-dates">' . $dateRange . '</div>';
        }

        $html .= '</div>';

        // Parents
        if (!empty($tree['parents'])) {
            $html .= '<div class="parents-container">';

            if (!empty($tree['parents']['father'])) {
                $html .= $this->renderPedigreeTree($tree['parents']['father'], $level + 1);
            }

            if (!empty($tree['parents']['mother'])) {
                $html .= $this->renderPedigreeTree($tree['parents']['mother'], $level + 1);
            }

            $html .= '</div>';
        }

        $html .= '</div>';

        return $html;
    }
}
