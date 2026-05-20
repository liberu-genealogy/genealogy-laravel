<?php

namespace App\Livewire;

use App\Models\UserChecklist;
use App\Models\Person;
use App\Models\Family;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;

class ResearchProgressWidget extends Component
{
    public int $selectedPeriod = 30; // days
    public string $selectedSubjectType = 'all';
    public bool $showDetails = false;

    /** @var array<string, mixed> */
    public array $stats = [
        'total_checklists' => 0,
        'completed_checklists' => 0,
        'in_progress_checklists' => 0,
        'completion_rate' => 0,
        'overall_progress' => 0,
    ];

    /** @var array<string, mixed> */
    public array $recentActivity = [
        'items' => [],
    ];

    /** @var array<string, Collection> */
    public array $upcomingDeadlines = [
        'overdue' => null,
        'upcoming' => null,
    ];

    /** @var array<string, mixed> */
    public array $subjectProgress = [
        'person_progress' => [
            'completed' => 0,
            'total' => 0,
            'progress_percentage' => 0,
        ],
        'family_progress' => [
            'completed' => 0,
            'total' => 0,
            'progress_percentage' => 0,
        ],
        'top_persons' => null,
        'top_families' => null,
    ];

    public function mount(): void
    {
        // Initialize collections to avoid null method calls in the Blade
        $this->upcomingDeadlines['overdue'] = collect();
        $this->upcomingDeadlines['upcoming'] = collect();
        $this->subjectProgress['top_persons'] = collect();
        $this->subjectProgress['top_families'] = collect();

        $this->refreshData();
    }

    public function updatedSelectedPeriod(): void
    {
        $this->refreshData();
    }

    public function updatedSelectedSubjectType(): void
    {
        $this->refreshData();
    }

    public function toggleDetails(): void
    {
        $this->showDetails = ! $this->showDetails;
    }

    public function render(): View
    {
        return view('livewire.research-progress-widget');
    }

    protected function refreshData(): void
    {
        $userId = auth()->id();
        if (! $userId) {
            return;
        }

        $periodStart = Carbon::now()->subDays($this->selectedPeriod);

        // Base query for user's checklists
        $base = UserChecklist::query()->where('user_id', $userId);
        if ($this->selectedSubjectType !== 'all') {
            $base->where('subject_type', $this->selectedSubjectType);
        }

        $total = (clone $base)->count();
        $completed = (clone $base)->completed()->count();
        $inProgress = (clone $base)->active()->count();

        $completionRate = $total > 0 ? round(($completed / $total) * 100, 2) : 0.0;
        // Overall progress approximated by completion rate when item-level data not available
        $overallProgress = $completionRate;

        $this->stats = [
            'total_checklists' => $total,
            'completed_checklists' => $completed,
            'in_progress_checklists' => $inProgress,
            'completion_rate' => $completionRate,
            'overall_progress' => $overallProgress,
        ];

        // Recent activity: latest completed checklists in period, adapted to Blade structure
        $recentCompleted = (clone $base)
            ->whereNotNull('completed_at')
            ->where('completed_at', '>=', $periodStart)
            ->orderByDesc('completed_at')
            ->limit(20)
            ->get(['id','name','completed_at','subject_type','subject_id']);

        // Map to objects with userChecklist relation-like structure expected by the Blade
        $this->recentActivity = [
            'items' => $recentCompleted->map(function (UserChecklist $c) {
                return (object) [
                    'userChecklist' => $c,
                    'completed_at' => $c->completed_at,
                ];
            }),
        ];

        // Upcoming deadlines
        $this->upcomingDeadlines['overdue'] = (clone $base)->overdue()->orderBy('due_date')->limit(20)->get();
        $this->upcomingDeadlines['upcoming'] = (clone $base)
            ->whereNotNull('due_date')
            ->where('due_date', '>=', Carbon::today())
            ->where('status', '!=', UserChecklist::STATUS_COMPLETED)
            ->orderBy('due_date')
            ->limit(20)
            ->get();

        // Subject progress
        $personTotal = (clone $base)->where('subject_type', Person::class)->count();
        $personCompleted = (clone $base)->where('subject_type', Person::class)->completed()->count();
        $familyTotal = (clone $base)->where('subject_type', Family::class)->count();
        $familyCompleted = (clone $base)->where('subject_type', Family::class)->completed()->count();

        $this->subjectProgress['person_progress'] = [
            'completed' => $personCompleted,
            'total' => $personTotal,
            'progress_percentage' => $personTotal > 0 ? round(($personCompleted / $personTotal) * 100, 2) : 0,
        ];
        $this->subjectProgress['family_progress'] = [
            'completed' => $familyCompleted,
            'total' => $familyTotal,
            'progress_percentage' => $familyTotal > 0 ? round(($familyCompleted / $familyTotal) * 100, 2) : 0,
        ];

        // Top subjects by checklist count (completed first, then total)
        $topPersons = (clone $base)
            ->where('subject_type', Person::class)
            ->selectRaw('subject_id, sum(case when status = ? then 1 else 0 end) as completed_count, count(*) as total_count', [UserChecklist::STATUS_COMPLETED])
            ->groupBy('subject_id')
            ->orderByDesc('completed_count')
            ->orderByDesc('total_count')
            ->limit(5)
            ->get()
            ->map(function ($row) {
                $person = Person::find($row->subject_id);
                if ($person) {
                    $person->progress_percentage = $row->total_count > 0 ? round(($row->completed_count / $row->total_count) * 100, 0) : 0;
                }
                return $person;
            })
            ->filter();

        $topFamilies = (clone $base)
            ->where('subject_type', Family::class)
            ->selectRaw('subject_id, sum(case when status = ? then 1 else 0 end) as completed_count, count(*) as total_count', [UserChecklist::STATUS_COMPLETED])
            ->groupBy('subject_id')
            ->orderByDesc('completed_count')
            ->orderByDesc('total_count')
            ->limit(5)
            ->get()
            ->map(function ($row) {
                $family = Family::find($row->subject_id);
                if ($family) {
                    $family->progress_percentage = $row->total_count > 0 ? round(($row->completed_count / $row->total_count) * 100, 0) : 0;
                }
                return $family;
            })
            ->filter();

        $this->subjectProgress['top_persons'] = $topPersons;
        $this->subjectProgress['top_families'] = $topFamilies;
    }
}
