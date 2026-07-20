<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Person;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class FanChartComponent extends Component
{
    /** @var array<string, mixed> */
    public array $tree = [];

    public function mount(?Person $person = null): void
    {
        $this->tree = $this->buildTree($person);
    }

    /**
     * Build the ancestor hierarchy the D3 fan chart expects: the selected person
     * as the root node, with children[] = father then mother, recursively. (In
     * an ancestor fan, d3's "children" are the parents.)
     *
     * @return array<string, mixed>
     */
    private function buildTree(?Person $person, int $generations = 4): array
    {
        if (! $person) {
            return [];
        }

        $node = [
            'id' => $person->id,
            'name' => $person->fullname(),
            'birth_year' => $person->birth_year,
            'death_year' => $person->death_year,
            'sex' => $person->sex,
            'children' => [],
        ];

        if ($generations > 0) {
            foreach ([$person->father(), $person->mother()] as $parent) {
                if ($parent) {
                    $node['children'][] = $this->buildTree($parent, $generations - 1);
                }
            }
        }

        return $node;
    }

    public function getColumnSpan(): int|string|array
    {
        return 'full';
    }

    public function getColumnStart(): int|string|array|null
    {
        return null;
    }

    public function render(): Factory|View
    {
        return view('livewire.fan-chart-component', ['tree' => $this->tree]);
    }
}
