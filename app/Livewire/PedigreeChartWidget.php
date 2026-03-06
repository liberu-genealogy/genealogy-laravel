<?php

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use App\Models\Person;
use Filament\Widgets\Widget;
use Livewire\Attributes\On;

class PedigreeChartWidget extends Widget
{
    use \App\Traits\PedigreeChartTrait;

    protected string $view = 'filament.widgets.pedigree-chart-widget';

    public function render(): View
    {
        return view(static::$view, $this->getData());
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
