<?php

namespace App\Http\Livewire;

use App\Models\Family;
use App\Models\Person;
use Livewire\Component;

/**
 * Class DabovilleReport extends Component.
 *
 * DabovilleReport - Class for generating a report based on a person's family tree.
 *
 * @var int   selectedPersonId The ID of the selected person for the report.
 * @var array reportData Array to store the report data.
 */
class DabovilleReport extends Component
{
    use \App\Traits\FamilyReportTrait;

    public function render()
    {
        return view('livewire.daboville-report');
    }
}
