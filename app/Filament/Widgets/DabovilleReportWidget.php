<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Person;

class DabovilleReportWidget extends Widget
{
    protected static string $view = 'livewire.daboville-report';

    public $selectedPersonId;
    public $reportData = [];

    public function mount()
    {
        // Initial setup can be done here if needed
    }

    public function generateReport($personId)
    {
        $this->selectedPersonId = $personId;
        $person = Person::find($personId);
        if ($person) {
            $this->reportData = [];
            $this->traverseFamilyTree($person, '1');
        }
    }

    private function traverseFamilyTree($person, $currentNumber)
/**
 * Defines the Daboville Report widget for displaying genealogy reports.
 */
    {
        $this->reportData[$person->id] = [
            'number' => $currentNumber,
            'name' => $person->fullname(),
            'birth' => optional($person->birth())->date,
            'death' => optional($person->death())->date,
        ];

        $childNumber = 1;
        foreach ($person->child_in_family as $child) {
            $this->traverseFamilyTree($child, $currentNumber . '.' . $childNumber);
            $childNumber++;
        }
    }

	public function render(): \Illuminate\Contracts\View\View
	{
	    return view(static::$view, ['reportData' => $this->reportData, 'selectedPersonId' => $this->selectedPersonId]);
	}

}
    /**
     * Renders the Daboville Report widget view with the necessary data.
     */
