<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Person;
use Livewire\Component;

class FanChartComponent extends Component
{
    public $people;

    public function getColumnSpan(): int|string|array
    {
        return 'full';
    }

    public function getColumnStart(): int|string|array|null
    {
        return null;
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $this->people = Person::all();

        return view('livewire.fan-chart-component', ['people' => $this->people]);
    }
}
