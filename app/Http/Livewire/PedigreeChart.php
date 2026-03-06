<?php

namespace App\Http\Livewire;

use App\Models\Person;
use Livewire\Component;

class PedigreeChart extends Component
{
    use \App\Traits\PedigreeChartTrait;

    protected $view = 'livewire.pedigree-chart';

    public function render()
    {
        return view('livewire.pedigree-chart', $this->getData());
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
