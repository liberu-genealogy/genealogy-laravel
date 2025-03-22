<?php

namespace App\Http\Livewire;

use App\Models\Person;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Collection;

final class FamilyTreeBuilder extends Component
{
    public Collection $treeData;
    public ?Person $selectedPerson = null;
    
    protected $listeners = [
        'personMoved' => 'updatePersonPosition',
        'personAdded' => 'addPerson',
        'personRemoved' => 'removePerson'
    ];

    public function mount(): void
    {
        $this->treeData = collect();
        $this->loadTreeData();
    }

    private function loadTreeData(): void
    {
        $this->treeData = Person::query()
            ->with(['childInFamily', 'familiesAsHusband', 'familiesAsWife'])
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
            ]);
    }

    public function updatePersonPosition($personId, $x, $y)
    {
        $person = Person::find($personId);
        if ($person) {
            $person->update([
                'tree_position_x' => $x,
                'tree_position_y' => $y
            ]);
            $this->emit('positionUpdated', $personId);
        }
    }

    public function addPerson($data)
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
        $this->emit('personCreated', $person->id);
    }

    public function removePerson($personId)
    {
        $person = Person::find($personId);
        if ($person) {
            $person->delete();
            $this->loadTreeData();
            $this->emit('personDeleted', $personId);
        }
    }

    public function render()
    {
        return view('livewire.family-tree-builder');
    }
}