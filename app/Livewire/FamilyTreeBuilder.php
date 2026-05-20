<?php

namespace App\Livewire;

use App\Models\Person;
use App\Models\Family;
use Livewire\Component;
use Livewire\Attributes\On;

final class FamilyTreeBuilder extends Component
{
    public array $treeData = [];
    public ?Person $selectedPerson = null;

    public function mount(): void
    {
        $this->loadTreeData();
    }

    private function loadTreeData(): void
    {
        $this->treeData = Person::with(['childInFamily', 'familiesAsHusband', 'familiesAsWife'])
            ->get()
            ->map(fn (Person $person): array => [
                'id' => $person->id,
                'name' => $person->fullname(),
                'position' => [
                    'x' => $person->tree_position_x ?? 0,
                    'y' => $person->tree_position_y ?? 0,
                ],
                'relationships' => [
                    'parent_family' => $person->child_in_family_id,
                    'spouse_families' => [
                        ...$person->familiesAsHusband->pluck('id'),
                        ...$person->familiesAsWife->pluck('id')
                    ]
                ]
            ])
            ->toArray();
    }

    #[On('personMoved')]
    public function updatePersonPosition(int $personId, float $x, float $y): void
    {
        $person = Person::find($personId);
        
        if (!$person) {
            $this->dispatch('error', message: 'Person not found');
            return;
        }
        
        $person->update([
            'tree_position_x' => $x,
            'tree_position_y' => $y
        ]);

        $this->dispatch('positionUpdated', personId: $personId);
    }

    #[On('personAdded')]
    public function addPerson(array $data): void
    {
        // Validate required fields
        if (empty($data['givn']) && empty($data['surn'])) {
            $this->dispatch('error', message: 'Either given name or surname is required');
            return;
        }
        
        $personData = [
            'givn' => $data['givn'] ?? '',
            'surn' => $data['surn'] ?? '',
            'sex' => $data['sex'] ?? 'U',
            'tree_position_x' => $data['position']['x'] ?? 0,
            'tree_position_y' => $data['position']['y'] ?? 0,
        ];
        
        // Add optional fields if provided
        if (isset($data['birthday'])) {
            $personData['birthday'] = $data['birthday'];
        }
        if (isset($data['child_in_family_id'])) {
            $personData['child_in_family_id'] = $data['child_in_family_id'];
        }

        $person = Person::create($personData);

        $this->loadTreeData();
        $this->dispatch('personCreated', personId: $person->id);
    }

    #[On('personRemoved')]
    public function removePerson(int $personId): void
    {
        $person = Person::find($personId);
        
        if (!$person) {
            $this->dispatch('error', message: 'Person not found');
            return;
        }
        
        $person->delete();
        $this->loadTreeData();
        $this->dispatch('personDeleted', personId: $personId);
    }

    public function selectPerson(int $personId): void
    {
        $this->selectedPerson = Person::find($personId);
        
        if (!$this->selectedPerson) {
            $this->dispatch('error', message: 'Person not found');
            return;
        }
        
        $this->dispatch('personSelected', personId: $personId);
    }

    public function render()
    {
        return view('livewire.family-tree-builder');
    }
}
