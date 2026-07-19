<?php

namespace App\Livewire;

use App\Models\ChecklistTemplate;
use App\Models\Family;
use App\Models\Person;
use App\Models\UserChecklist;
use App\Models\UserChecklistItem;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class UserChecklistManager extends Component
{
    use WithPagination;

    public $showCreateModal = false;

    public $showEditModal = false;

    public $showItemModal = false;

    public $selectedChecklist = null;

    public $selectedItem = null;

    public $selectedTemplate = null;

    // Form properties
    #[Rule('required|string|max:255')]
    public $name = '';

    #[Rule('nullable|string')]
    public $description = '';

    #[Rule('nullable|string')]
    public $subject_type = '';

    #[Rule('nullable|integer')]
    public $subject_id = '';

    #[Rule('required|in:low,medium,high,urgent')]
    public $priority = 'medium';

    #[Rule('nullable|date')]
    public $due_date = '';

    #[Rule('nullable|string')]
    public $notes = '';

    // Item form properties
    public $item_title = '';

    public $item_description = '';

    public $item_notes = '';

    public $item_estimated_time = '';

    public $item_actual_time = '';

    // Filters
    public $statusFilter = 'all';

    public $priorityFilter = 'all';

    public $subjectFilter = 'all';

    public $search = '';

    protected $itemRules = [
        'item_title' => 'required|string|max:255',
        'item_description' => 'nullable|string',
        'item_notes' => 'nullable|string',
        'item_estimated_time' => 'nullable|integer|min:1',
        'item_actual_time' => 'nullable|integer|min:1',
    ];

    public function mount(): void
    {
        $this->resetFilters();
    }

    public function render(): Factory|View
    {
        $checklists = $this->getFilteredChecklists();
        $templates = ChecklistTemplate::where('is_public', true)
            ->orWhere('created_by', Auth::id())
            ->orderBy('name')
            ->get();

        $persons = Person::orderBy('name')->get();
        $families = Family::orderBy('id')->get();

        return view('livewire.user-checklist-manager', [
            'checklists' => $checklists,
            'templates' => $templates,
            'persons' => $persons,
            'families' => $families,
        ]);
    }

    public function getFilteredChecklists()
    {
        $query = UserChecklist::where('user_id', Auth::id())
            ->with(['template', 'items', 'subject']);

        // Apply search filter
        if (! empty($this->search)) {
            $query->where(function ($q): void {
                $q->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('description', 'like', '%'.$this->search.'%');
            });
        }

        // Apply status filter
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        // Apply priority filter
        if ($this->priorityFilter !== 'all') {
            $query->where('priority', $this->priorityFilter);
        }

        // Apply subject filter
        if ($this->subjectFilter !== 'all') {
            $query->where('subject_type', $this->subjectFilter);
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }

    public function createFromTemplate($templateId): void
    {
        $template = ChecklistTemplate::with('templateItems')->findOrFail($templateId);

        $this->selectedTemplate = $template;
        $this->name = $template->name;
        $this->description = $template->description;
        $this->showCreateModal = true;
    }

    public function createChecklist(): void
    {
        $this->validate();

        $checklist = UserChecklist::create([
            'user_id' => Auth::id(),
            'checklist_template_id' => $this->selectedTemplate?->id,
            'name' => $this->name,
            'description' => $this->description,
            'subject_type' => $this->subject_type ?: null,
            'subject_id' => $this->subject_id ?: null,
            'priority' => $this->priority,
            'due_date' => $this->due_date ?: null,
            'notes' => $this->notes,
            'status' => UserChecklist::STATUS_NOT_STARTED,
        ]);

        // Create items from template if selected
        if ($this->selectedTemplate) {
            foreach ($this->selectedTemplate->templateItems as $templateItem) {
                UserChecklistItem::create([
                    'user_checklist_id' => $checklist->id,
                    'checklist_template_item_id' => $templateItem->id,
                    'title' => $templateItem->title,
                    'description' => $templateItem->description,
                    'order' => $templateItem->order,
                    'estimated_time' => $templateItem->estimated_time,
                    'resources' => $templateItem->resources,
                    'tips' => $templateItem->tips,
                ]);
            }
        }

        $this->resetForm();
        $this->showCreateModal = false;
        $this->dispatch('checklist-created');
        session()->flash('message', 'Checklist created successfully!');
    }

    /**
     * A checklist the acting user owns, or a 404.
     *
     * Research checklists belong to a user. The checklist table is tenant-scoped
     * so another team's rows are already invisible, but that left the cross-user
     * case within one team open — every method below loaded straight from a
     * request id with no owner check, so a member could edit or delete a
     * teammate's private checklist by guessing its id. Ownership is the rule
     * here, not a collaboration tier, so it is a where clause rather than the
     * tier trait. Loading through here on every by-id path is what closes it;
     * guarding only the edit entry point would miss updateChecklist, which acts
     * on a hydrated property a crafted request controls.
     */
    private function ownedChecklist($checklistId): UserChecklist
    {
        return UserChecklist::where('user_id', Auth::id())->findOrFail($checklistId);
    }

    /**
     * An item on a checklist the acting user owns, or a 404.
     *
     * Items carry no team and no user of their own — unlike the checklist, the
     * item table is not tenant-scoped — so an item id was reachable across both
     * team and user. Ownership is proven through the parent checklist.
     */
    private function ownedItem($itemId): UserChecklistItem
    {
        return UserChecklistItem::whereHas(
            'userChecklist',
            fn ($query) => $query->where('user_id', Auth::id()),
        )->findOrFail($itemId);
    }

    public function editChecklist($checklistId): void
    {
        $this->selectedChecklist = $this->ownedChecklist($checklistId);
        $this->name = $this->selectedChecklist->name;
        $this->description = $this->selectedChecklist->description;
        $this->subject_type = $this->selectedChecklist->subject_type;
        $this->subject_id = $this->selectedChecklist->subject_id;
        $this->priority = $this->selectedChecklist->priority;
        $this->due_date = $this->selectedChecklist->due_date?->format('Y-m-d');
        $this->notes = $this->selectedChecklist->notes;
        $this->showEditModal = true;
    }

    public function updateChecklist(): void
    {
        $this->validate();

        // Re-resolved through the ownership check rather than trusting the
        // hydrated property: Livewire re-queries selectedChecklist by key on
        // every round-trip with no scope, so a crafted request could point it
        // at a teammate's checklist between edit and update.
        $checklist = $this->ownedChecklist($this->selectedChecklist->id);

        $checklist->update([
            'name' => $this->name,
            'description' => $this->description,
            'subject_type' => $this->subject_type ?: null,
            'subject_id' => $this->subject_id ?: null,
            'priority' => $this->priority,
            'due_date' => $this->due_date ?: null,
            'notes' => $this->notes,
        ]);

        $this->resetForm();
        $this->showEditModal = false;
        $this->dispatch('checklist-updated');
        session()->flash('message', 'Checklist updated successfully!');
    }

    public function deleteChecklist($checklistId): void
    {
        $this->ownedChecklist($checklistId)->delete();
        $this->dispatch('checklist-deleted');
        session()->flash('message', 'Checklist deleted successfully!');
    }

    public function toggleItemCompletion($itemId): void
    {
        $item = $this->ownedItem($itemId);

        if ($item->is_completed) {
            $item->markAsIncomplete();
        } else {
            $item->markAsCompleted();
        }

        $this->dispatch('item-toggled');
    }

    public function editItem($itemId): void
    {
        $this->selectedItem = $this->ownedItem($itemId);
        $this->item_title = $this->selectedItem->title;
        $this->item_description = $this->selectedItem->description;
        $this->item_notes = $this->selectedItem->notes;
        $this->item_estimated_time = $this->selectedItem->estimated_time;
        $this->item_actual_time = $this->selectedItem->actual_time;
        $this->showItemModal = true;
    }

    public function updateItem(): void
    {
        $this->validate($this->itemRules);

        // Re-resolved through ownership, not trusting the hydrated property —
        // see updateChecklist.
        $item = $this->ownedItem($this->selectedItem->id);

        $item->update([
            'title' => $this->item_title,
            'description' => $this->item_description,
            'notes' => $this->item_notes,
            'estimated_time' => $this->item_estimated_time,
            'actual_time' => $this->item_actual_time,
        ]);

        $this->resetItemForm();
        $this->showItemModal = false;
        $this->dispatch('item-updated');
        session()->flash('message', 'Item updated successfully!');
    }

    public function addCustomItem($checklistId): void
    {
        $checklist = $this->ownedChecklist($checklistId);
        $maxOrder = $checklist->items()->max('order') ?? 0;

        UserChecklistItem::create([
            'user_checklist_id' => $checklistId,
            'title' => 'New Custom Item',
            'description' => 'Add your custom research task here',
            'order' => $maxOrder + 1,
        ]);

        $this->dispatch('item-added');
        session()->flash('message', 'Custom item added successfully!');
    }

    public function resetFilters(): void
    {
        $this->statusFilter = 'all';
        $this->priorityFilter = 'all';
        $this->subjectFilter = 'all';
        $this->search = '';
        $this->resetPage();
    }

    public function resetForm(): void
    {
        $this->name = '';
        $this->description = '';
        $this->subject_type = '';
        $this->subject_id = '';
        $this->priority = 'medium';
        $this->due_date = '';
        $this->notes = '';
        $this->selectedTemplate = null;
        $this->selectedChecklist = null;
    }

    public function resetItemForm(): void
    {
        $this->item_title = '';
        $this->item_description = '';
        $this->item_notes = '';
        $this->item_estimated_time = '';
        $this->item_actual_time = '';
        $this->selectedItem = null;
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatedPriorityFilter(): void
    {
        $this->resetPage();
    }

    public function updatedSubjectFilter(): void
    {
        $this->resetPage();
    }
}
