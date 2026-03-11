<?php

namespace App\Livewire;

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

    public function generateReport($personId): void
    {
        $this->selectedPersonId = $personId;
        $person = Person::with(['events', 'familiesAsHusband.children', 'familiesAsWife.children'])->find($personId);
        if ($person) {
            $this->reportData = [];
            $this->processHenryReportData($person);
        }
    }

    private function processHenryReportData($person): void
    {
        $this->reportData[$person->id] = [
            'name' => $person->fullname(),
            'birth' => optional($person->birth())->date,
            'death' => optional($person->death())->date,
        ];

        $children = $person->children ?? collect();
        foreach ($children as $child) {
            $this->processHenryReportData($child);
        }
    }
}
