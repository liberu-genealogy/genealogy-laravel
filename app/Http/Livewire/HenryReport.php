<?php

namespace App\Http\Livewire;

use App\Models\Person;
use Livewire\Component;

class HenryReport extends Component
{
    public $selectedPersonId;
    public $reportData = [];

    public function render()
    {
        return view('livewire.henry-report', ['reportData' => $this->reportData]);
    }

    public function generateReport($personId)
    {
        $this->selectedPersonId = $personId;
        $person = Person::with('child_in_family.birth', 'child_in_family.death')->find($personId);
        if ($person) {
            $this->reportData = [];
            $this->processHenryReportData($person);
        }
    }

    private function processHenryReportData($person)
    {
        $this->reportData[$person->id] = [
            'name'  => $person->fullname(),
            'birth' => optional($person->birth())->date,
            'death' => optional($person->death())->date,
        ];

        $childNumber = 1;
        foreach ($person->child_in_family as $child) {
            $this->processHenryReportData($child, $childNumber);
            $childNumber++;
        }
    }
}
