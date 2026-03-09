<?php

namespace App\Http\Livewire\ResearchSpace;

use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\ResearchSpace;
use App\Events\ResearchSpaceUpdated;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CollaboratorBoard extends Component
{
    use AuthorizesRequests;

    public ResearchSpace $space;
    public int $spaceId = 0;
    public $content = '';
    public $userPermissions = [];

    public function mount($spaceId)
    {
        $this->space = ResearchSpace::with('collaborators.user')->findOrFail($spaceId);
        $this->spaceId = $this->space->id;

        $this->authorize('view', $this->space);

        // Example initial content stored in settings (could be moved to separate table)
        $this->content = data_get($this->space->settings, 'board.content', '');
        $this->userPermissions = []; // can be populated from collaborators
    }

    public function saveContent()
    {
        $this->authorize('update', $this->space);

        $settings = $this->space->settings ?? [];
        $settings['board'] = array_merge($settings['board'] ?? [], ['content' => $this->content, 'updated_at' => now()->toDateTimeString()]);
        $this->space->settings = $settings;
        $this->space->save();

        // Broadcast to other collaborators immediately
        event(new ResearchSpaceUpdated($this->space->id, ['content' => $this->content, 'user_id' => auth()->id()]));
    }

    #[On('echo:research-space.{spaceId},ResearchSpaceUpdated')]
    public function onExternalUpdate($payload)
    {
        // When we get an external broadcast, update local content
        $this->content = data_get($payload, 'content', $this->content);
        $this->dispatch('contentUpdated');
    }

    public function render()
    {
        return view('livewire.research-space.collaborator-board', [
            'space' => $this->space,
        ]);
    }
}
