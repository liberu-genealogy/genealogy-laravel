<?php

namespace App\Livewire;

use App\Concerns\AuthorizesCollaborationTier;
use App\Models\Person;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * Builds and edits the people a team records.
 *
 * These methods write the team's genealogy — creating, moving and deleting
 * Person records — and they are Livewire methods on a plain web route, so they
 * are addressable over the wire whether or not the interface offers them and
 * nothing upstream authorises them. Each therefore guards itself against the
 * collaboration tier the user holds in their current team: a viewer reads, a
 * contributor adds and edits, an editor deletes.
 *
 * There is no panel tenant on this route, so the tier resolves against the
 * user's current team — the same team BelongsToTenant scopes the records to.
 */
final class FamilyTreeBuilder extends Component
{
    use AuthorizesCollaborationTier;

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
                        ...$person->familiesAsWife->pluck('id'),
                    ],
                ],
            ])
            ->toArray();
    }

    #[On('personMoved')]
    public function updatePersonPosition(int $personId, float $x, float $y): void
    {
        $this->authorizeCollaborationTier('update');

        $person = Person::find($personId);

        if (! $person) {
            $this->dispatch('error', message: 'Person not found');

            return;
        }

        $person->update([
            'tree_position_x' => $x,
            'tree_position_y' => $y,
        ]);

        $this->dispatch('positionUpdated', personId: $personId);
    }

    #[On('personAdded')]
    public function addPerson(array $data): void
    {
        $this->authorizeCollaborationTier('create');

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
        $this->authorizeCollaborationTier('delete');

        $person = Person::find($personId);

        if (! $person) {
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

        if (! $this->selectedPerson instanceof Person) {
            $this->dispatch('error', message: 'Person not found');

            return;
        }

        $this->dispatch('personSelected', personId: $personId);
    }

    public function render(): Factory|View
    {
        return view('livewire.family-tree-builder');
    }
}
