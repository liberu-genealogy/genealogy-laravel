<?php

namespace App\Http\Livewire;

use App\Models\Person;
use Livewire\Component;

class AhnentafelReport extends Component
{
    use \App\Traits\FamilyReportTrait;

    public function render()
    {
        return view('livewire.ahnentafel-report');
    }
}
