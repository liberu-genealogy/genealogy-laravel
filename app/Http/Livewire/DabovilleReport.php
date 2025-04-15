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
    public $selectedPersonId;
    public $reportData = [];

    /**
     * Renders the DabovilleReport component.
     *
     * @return view
     */
    public function render()
    {
        return view('livewire.daboville-report');
    }

    /**
     * Generates a report based on the provided person ID.
     *
     * @param int personId The ID of the person to generate the report for.
     */
    public function generateReport($personId): void
    {
        $this->selectedPersonId = $personId;
        $person = Person::with('child_in_family.birth', 'child_in_family.death')->find($personId);
        if ($person) {
            $this->reportData = [];
            $this->traverseFamilyTree($person, '1');
        }
    }

    /**
     * Traverses the family tree starting from the given person.
     *
     * @param Person person The person to start the traversal from.
     * @param string currentNumber The current number in the family tree.
     */
    private function traverseFamilyTree($person, string $currentNumber): void
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
