<?php

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use App\Models\Person;
use Livewire\Component;

class FanChart extends Component
{
    use \App\Traits\FanChartTrait;

    protected string $view = 'livewire.fan-chart';

    public function render(): View
    {
        return view($this->view, $this->getData());
    }

    public function getPeopleListProperty(): array
    {
        return Person::getListOptimized();
    }
}
