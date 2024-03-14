<?php

namespace App\Http\Livewire;

use App\Models\Person;
use Livewire\Component;

class AhnentafelReport extends Component
{
    public $selectedPersonId;
    public $reportData = [];

    public function render()
    {
        return view('livewire.ahnentafel-report');
    }
}
