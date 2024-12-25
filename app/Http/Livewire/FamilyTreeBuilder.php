

<?php

namespace App\Http\Livewire;

use App\Models\Person;
use App\Models\Family;
use Livewire\Component;

class FamilyTreeBuilder extends Component
{
    public $treeData = [];
    public $selectedPerson = null;
    
    protected $listeners = [
        'personMoved' => 'updatePersonPosition',
        'personAdded' => 'addPerson',
        'personRemoved' => 'removePerson'
    ];

    public function mount()
    {
        $this->loadTreeData();
    }

    public function loadTreeData()
    {
        $people = Person::with(['childInFamily', 'familiesAsHusband', 'familiesAsWife'])
            ->get()
            ->map(function ($person) {
                return [
                    'id' => $person->id,
                    'name' => $person->fullname(),
                    'position' => [
                        'x' => $person->tree_position_x ?? 0,
                        'y' => $person->tree_position_y ?? 0,
                    ],
                    'relationships' => [
                        'parent_family' => $person->child_in_family_id,
                        'spouse_families' => array_merge(
                            $person->familiesAsHusband->pluck('id')->toArray(),
                            $person->familiesAsWife->pluck('id')->toArray()
                        )
                    ]
                ];
            });
        
        $this->treeData = $people->toArray();
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