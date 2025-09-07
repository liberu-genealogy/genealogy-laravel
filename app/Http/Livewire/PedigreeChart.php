<?php

namespace App\Http\Livewire;

use App\Models\Person;
use Livewire\Component;

class PedigreeChart extends Component
{
    protected $view = 'livewire.pedigree-chart';

    public $rootPersonId = null;
    public $generations = 4;
    public $showDates = true;
    public $showPhotos = false;

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

    public function render()
    {
        return view('livewire.pedigree-chart', $this->getData());
    }

    public function setRootPerson($personId): void
    {
        $this->rootPersonId = $personId;
        $this->dispatch('refreshChart');
    }

    public function setGenerations($generations): void
    {
        $this->generations = max(1, min(6, $generations));
        $this->dispatch('refreshChart');
    }

    public function toggleDates(): void
    {
        $this->showDates = !$this->showDates;
        $this->dispatch('refreshChart');
    }

    public function togglePhotos(): void
    {
        $this->showPhotos = !$this->showPhotos;
        $this->dispatch('refreshChart');
    }

    public function expandPerson($personId): void
    {
        $this->setRootPerson($personId);
    }

    public function renderPedigreeTree($tree, $level = 0): string
    {
        if (empty($tree)) {
            return '<div class="empty-person-box">No data</div>';
        }

        $html = '<div class="generation-level level-' . $level . '" data-level="' . $level . '">';

        // Add connection line if not root level
        if ($level > 0) {
            $html .= '<div class="connection-line"></div>';
        }

        // Person box
        $sexClass = strtolower($tree['sex'] ?? 'unknown');
        $html .= '<div class="person-box ' . $sexClass . '" onclick="expandPerson(' . $tree['id'] . ')" data-person-id="' . $tree['id'] . '">';
        $html .= '<button class="expand-btn" title="Expand from this person">↑</button>';
        $html .= '<div class="person-name">' . htmlspecialchars($tree['name'] ?? 'Unknown') . '</div>';

        if ($this->showDates) {
            $birthDate = $tree['birth_date'] ? date('Y', strtotime($tree['birth_date'])) : '?';
            $deathDate = $tree['death_date'] ? date('Y', strtotime($tree['death_date'])) : '';
            $dateRange = $birthDate . ($deathDate ? ' - ' . $deathDate : ($birthDate !== '?' ? ' - ' : ''));
            $html .= '<div class="person-dates">' . $dateRange . '</div>';
        }

        $html .= '</div>';

        // Parents
        if (!empty($tree['parents']) && ($level < $this->generations - 1)) {
            $html .= '<div class="parents-container">';

            if (!empty($tree['parents']['father'])) {
                $html .= '<div class="parent-branch father-branch">';
                $html .= $this->renderPedigreeTree($tree['parents']['father'], $level + 1);
                $html .= '</div>';
            }

            if (!empty($tree['parents']['mother'])) {
                $html .= '<div class="parent-branch mother-branch">';
                $html .= $this->renderPedigreeTree($tree['parents']['mother'], $level + 1);
                $html .= '</div>';
            }

            $html .= '</div>';
        }

        $html .= '</div>';

        return $html;
    }
}
