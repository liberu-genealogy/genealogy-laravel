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

    protected array $listeners = [
        'personMoved' => 'updatePersonPosition',
        'personAdded' => 'addPerson',
        'personRemoved' => 'removePerson'
    ];

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

    #[On('updatePosition')]
    public function updatePersonPosition(int $personId, float $x, float $y): void
    {
        Person::find($personId)?->update([
            'tree_position_x' => $x,
            'tree_position_y' => $y
        ]);

        $this->dispatch('positionUpdated', personId: $personId);
    }

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
        $this->dispatch('personCreated', personId: $person->id);
    }

    public function removePerson(int $personId): void
    {
        Person::find($personId)?->delete();
        $this->loadTreeData();
        $this->dispatch('personDeleted', personId: $personId);
    }

    public function render()
    {
        return view('livewire.family-tree-builder');
    }
}