<?php

namespace App\Http\Livewire;

use App\Models\Person;
use Filament\Widgets\Widget;

class FanChart extends Component
{
    use \App\Traits\FanChartTrait;

    protected $view = 'livewire.fan-chart';

    public function render()
    {
        return view('livewire.fan-chart', $this->getData());
    }
}
