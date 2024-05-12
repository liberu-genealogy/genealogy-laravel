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

    public function generateReport($personId)
    {
        $this->selectedPersonId = $personId;
        $person = Person::with('child_in_family.birth', 'child_in_family.death')->find($personId);
        if ($person) {
            $this->reportData = [];
            $this->traverseFamilyTree($person, '1');
        }
    }

    private function traverseFamilyTree($person, $currentNumber)
    {
        $this->reportData[$person->id] = [
            'number' => $currentNumber,
            'name'   => $person->fullname(),
            'birth'  => optional($person->birth())->date,
            'death'  => optional($person->death())->date,
        ];

        $childNumber = 1;
        foreach ($person->child_in_family as $child) {
            $this->traverseFamilyTree($child, $currentNumber.'.'.$childNumber);
            $childNumber++;
        }
    }
}
