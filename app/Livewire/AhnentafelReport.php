<?php

namespace App\Livewire;

use App\Models\Person;
use Livewire\Component;

class AhnentafelReport extends Component
{
    public ?int $selectedPersonId = null;
    public array $reportData = [];

    public function render()
    {
        return view('livewire.ahnentafel-report');
    }

    public function generateReport(int $personId): void
    {
        $this->selectedPersonId = $personId;
        $person = Person::with(['childInFamily.husband', 'childInFamily.wife'])->find($personId);
        
        if ($person) {
            $this->reportData = [];
            $this->buildAhnentafelNumbers($person, 1);
            ksort($this->reportData);
        }
    }

    private function buildAhnentafelNumbers($person, int $number): void
    {
        if (!$person) {
            return;
        }

        $this->reportData[$number] = [
            'number' => $number,
            'person_id' => $person->id,
            'name' => $person->fullname(),
            'givn' => $person->givn,
            'surn' => $person->surn,
            'sex' => $person->sex,
            'birth_date' => $person->birthday?->format('d M Y'),
            'death_date' => $person->deathday?->format('d M Y'),
            'birth_place' => $person->birth_place ?? '',
            'death_place' => $person->death_place ?? '',
        ];

        // Build ancestors using Ahnentafel numbering system
        if ($person->childInFamily) {
            $family = $person->childInFamily;
            
            // Father gets number * 2
            if ($family->husband) {
                $this->buildAhnentafelNumbers($family->husband, $number * 2);
            }
            
            // Mother gets number * 2 + 1
            if ($family->wife) {
                $this->buildAhnentafelNumbers($family->wife, $number * 2 + 1);
            }
        }
    }

    public function clearReport(): void
    {
        $this->selectedPersonId = null;
        $this->reportData = [];
    }
}