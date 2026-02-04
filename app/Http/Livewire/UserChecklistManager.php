<?php

namespace App\Http\Livewire;

use App\Models\ChecklistTemplate;
use App\Models\UserChecklist;
use App\Models\UserChecklistItem;
use App\Models\Person;
use App\Models\Family;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

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
    public $name = '';
    public $description = '';
    public $subject_type = '';
    public $subject_id = '';
    public $priority = 'medium';
    public $due_date = '';
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

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'subject_type' => 'nullable|string',
        'subject_id' => 'nullable|integer',
        'priority' => 'required|in:low,medium,high,urgent',
        'due_date' => 'nullable|date',
        'notes' => 'nullable|string',
    ];

    protected $itemRules = [
        'item_title' => 'required|string|max:255',
        'item_description' => 'nullable|string',
        'item_notes' => 'nullable|string',
        'item_estimated_time' => 'nullable|integer|min:1',
        'item_actual_time' => 'nullable|integer|min:1',
    ];

    public function mount()
    {
        $this->resetFilters();
    }

    public function render()
    {
        $checklists = $this->getFilteredChecklists();
        $templates = ChecklistTemplate::where('is_public', true)
            ->orWhere('created_by', Auth::id())
            ->orderBy('name')
            ->get();

        $persons = Person::orderBy('name')->get();
        $families = Family::orderBy('name')->get();

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
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
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

    public function createFromTemplate($templateId)
    {
        $template = ChecklistTemplate::with('templateItems')->findOrFail($templateId);

        $this->selectedTemplate = $template;
        $this->name = $template->name;
        $this->description = $template->description;
        $this->showCreateModal = true;
    }

    public function createChecklist()
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
        $this->emit('checklist-created');
        session()->flash('message', 'Checklist created successfully!');
    }

    public function editChecklist($checklistId)
    {
        $this->selectedChecklist = UserChecklist::findOrFail($checklistId);
        $this->name = $this->selectedChecklist->name;
        $this->description = $this->selectedChecklist->description;
        $this->subject_type = $this->selectedChecklist->subject_type;
        $this->subject_id = $this->selectedChecklist->subject_id;
        $this->priority = $this->selectedChecklist->priority;
        $this->due_date = $this->selectedChecklist->due_date?->format('Y-m-d');
        $this->notes = $this->selectedChecklist->notes;
        $this->showEditModal = true;
    }

    public function updateChecklist()
    {
        $this->validate();

        $this->selectedChecklist->update([
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
        $this->emit('checklist-updated');
        session()->flash('message', 'Checklist updated successfully!');
    }

    public function deleteChecklist($checklistId)
    {
        UserChecklist::findOrFail($checklistId)->delete();
        $this->emit('checklist-deleted');
        session()->flash('message', 'Checklist deleted successfully!');
    }

    public function toggleItemCompletion($itemId)
    {
        $item = UserChecklistItem::findOrFail($itemId);

        if ($item->is_completed) {
            $item->markAsIncomplete();
        } else {
            $item->markAsCompleted();
        }

        $this->emit('item-toggled');
    }

    public function editItem($itemId)
    {
        $this->selectedItem = UserChecklistItem::findOrFail($itemId);
        $this->item_title = $this->selectedItem->title;
        $this->item_description = $this->selectedItem->description;
        $this->item_notes = $this->selectedItem->notes;
        $this->item_estimated_time = $this->selectedItem->estimated_time;
        $this->item_actual_time = $this->selectedItem->actual_time;
        $this->showItemModal = true;
    }

    public function updateItem()
    {
        $this->validate($this->itemRules);

        $this->selectedItem->update([
            'title' => $this->item_title,
            'description' => $this->item_description,
            'notes' => $this->item_notes,
            'estimated_time' => $this->item_estimated_time,
            'actual_time' => $this->item_actual_time,
        ]);

        $this->resetItemForm();
        $this->showItemModal = false;
        $this->emit('item-updated');
        session()->flash('message', 'Item updated successfully!');
    }

    public function addCustomItem($checklistId)
    {
        $checklist = UserChecklist::findOrFail($checklistId);
        $maxOrder = $checklist->items()->max('order') ?? 0;

        UserChecklistItem::create([
            'user_checklist_id' => $checklistId,
            'title' => 'New Custom Item',
            'description' => 'Add your custom research task here',
            'order' => $maxOrder + 1,
        ]);

        $this->emit('item-added');
        session()->flash('message', 'Custom item added successfully!');
    }

    public function resetFilters()
    {
        $this->statusFilter = 'all';
        $this->priorityFilter = 'all';
        $this->subjectFilter = 'all';
        $this->search = '';
        $this->resetPage();
    }

    public function resetForm()
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

    public function resetItemForm()
    {
        $this->item_title = '';
        $this->item_description = '';
        $this->item_notes = '';
        $this->item_estimated_time = '';
        $this->item_actual_time = '';
        $this->selectedItem = null;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedPriorityFilter()
    {
        $this->resetPage();
    }

    public function updatedSubjectFilter()
    {
        $this->resetPage();
    }
}

