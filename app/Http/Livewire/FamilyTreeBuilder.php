<?php

namespace App\Http\Livewire;

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
        Person::find($personId)?->update([
            'tree_position_x' => $x,
            'tree_position_y' => $y
        ]);

        $this->emit('positionUpdated', personId: $personId);
    }

    #[On('personAdded')]
    public function addPerson(array $data): void
    {
        $person = Person::create([
            'name' => $data['name'],
            'givn' => $data['givn'] ?? '',
            'surn' => $data['surn'] ?? '',
            'sex' => $data['sex'] ?? 'U',
            'tree_position_x' => $data['position']['x'],
            'tree_position_y' => $data['position']['y']
        ]);

        $this->loadTreeData();
        $this->emit('personCreated', personId: $person->id);
    }

    #[On('personRemoved')]
    public function removePerson(int $personId): void
    {
        Person::find($personId)?->delete();
        $this->loadTreeData();
        $this->emit('personDeleted', personId: $personId);
    }

    public function render()
    {
        return view('livewire.family-tree-builder');
    }
}
