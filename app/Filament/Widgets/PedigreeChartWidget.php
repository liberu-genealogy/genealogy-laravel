<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Person;

class PedigreeChartWidget extends Widget
{
    protected static string $view = 'livewire.pedigree-chart';

    public $persons;

    public function mount()
    {
        $this->persons = Person::all();
    }

    public function render()
    {
        return view(static::$view, ['persons' => $this->persons]);
    }
}
