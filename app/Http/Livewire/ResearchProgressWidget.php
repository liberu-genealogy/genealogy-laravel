<?php

namespace App\Http\Livewire;

use App\Models\UserChecklistItem;
use App\Models\UserChecklist;
use App\Models\Person;
use App\Models\Family;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ResearchProgressWidget extends Component
{
    public $selectedPeriod = '30'; // days
    public $selectedSubjectType = 'all';
    public $showDetails = false;

    public function render()
    {
        $stats = $this->getProgressStats();
        $recentActivity = $this->getRecentActivity();
        $upcomingDeadlines = $this->getUpcomingDeadlines();
        $subjectProgress = $this->getSubjectProgress();

        return view('livewire.research-progress-widget', [
            'stats' => $stats,
            'recentActivity' => $recentActivity,
            'upcomingDeadlines' => $upcomingDeadlines,
            'subjectProgress' => $subjectProgress,
        ]);
    }

    protected function getProgressStats(): array
    {
        $userId = Auth::id();
        $periodStart = now()->subDays((int) $this->selectedPeriod);

        $query = UserChecklist::where('user_id', $userId);

        if ($this->selectedSubjectType !== 'all') {
            $query->where('subject_type', $this->selectedSubjectType);
        }

        $totalChecklists = $query->count();
        $completedChecklists = $query->where('status', UserChecklist::STATUS_COMPLETED)->count();
        $inProgressChecklists = $query->where('status', UserChecklist::STATUS_IN_PROGRESS)->count();
        $overdueChecklists = $query->where('due_date', '<', now())
            ->where('status', '!=', UserChecklist::STATUS_COMPLETED)
            ->count();

        // Recent activity in selected period
        $recentCompletions = UserChecklist::where('user_id', $userId)
            ->where('completed_at', '>=', $periodStart)
            ->count();

        $recentItemCompletions = UserChecklistItem::whereHas('userChecklist', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->where('completed_at', '>=', $periodStart)
            ->count();

        // Calculate overall progress percentage
        $overallProgress = $totalChecklists > 0 ? round(($completedChecklists / $totalChecklists) * 100, 1) : 0;

        return [
            'total_checklists' => $totalChecklists,
            'completed_checklists' => $completedChecklists,
            'in_progress_checklists' => $inProgressChecklists,
            'overdue_checklists' => $overdueChecklists,
            'recent_completions' => $recentCompletions,
            'recent_item_completions' => $recentItemCompletions,
            'overall_progress' => $overallProgress,
            'completion_rate' => $totalChecklists > 0 ? round(($completedChecklists / $totalChecklists) * 100, 1) : 0,
        ];
    }

    protected function getRecentActivity(): array
    {
        $userId = Auth::id();
        $periodStart = now()->subDays((int) $this->selectedPeriod);

        // Recent checklist completions
        $recentChecklists = UserChecklist::where('user_id', $userId)
            ->where('completed_at', '>=', $periodStart)
            ->with('subject')
            ->orderBy('completed_at', 'desc')
            ->limit(5)
            ->get();

        // Recent item completions
        $recentItems = UserChecklistItem::whereHas('userChecklist', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->where('completed_at', '>=', $periodStart)
            ->with('userChecklist.subject')
            ->orderBy('completed_at', 'desc')
            ->limit(10)
            ->get();

        return [
            'checklists' => $recentChecklists,
            'items' => $recentItems,
        ];
    }

    protected function getUpcomingDeadlines(): array
    {
        $userId = Auth::id();
        $nextWeek = now()->addWeek();

        $upcomingChecklists = UserChecklist::where('user_id', $userId)
            ->where('status', '!=', UserChecklist::STATUS_COMPLETED)
            ->where('due_date', '<=', $nextWeek)
            ->where('due_date', '>=', now())
            ->with('subject')
            ->orderBy('due_date')
            ->get();

        $overdueChecklists = UserChecklist::where('user_id', $userId)
            ->where('status', '!=', UserChecklist::STATUS_COMPLETED)
            ->where('due_date', '<', now())
            ->with('subject')
            ->orderBy('due_date')
            ->get();

        return [
            'upcoming' => $upcomingChecklists,
            'overdue' => $overdueChecklists,
        ];
    }

    protected function getSubjectProgress(): array
    {
        $userId = Auth::id();

        // Progress by subject type
        $personProgress = $this->getSubjectTypeProgress('App\Models\Person');
        $familyProgress = $this->getSubjectTypeProgress('App\Models\Family');

        // Top researched subjects
        $topPersons = Person::whereHas('checklists', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->withCount(['checklists as total_checklists' => function ($q) use ($userId) {
                $q->where('user_id', $userId);
            }])
            ->withCount(['checklists as completed_checklists' => function ($q) use ($userId) {
                $q->where('user_id', $userId)->where('status', UserChecklist::STATUS_COMPLETED);
            }])
            ->orderBy('total_checklists', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($person) {
                $person->progress_percentage = $person->total_checklists > 0 
                    ? round(($person->completed_checklists / $person->total_checklists) * 100, 1) 
                    : 0;
                return $person;
            });

        $topFamilies = Family::whereHas('checklists', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->withCount(['checklists as total_checklists' => function ($q) use ($userId) {
                $q->where('user_id', $userId);
            }])
            ->withCount(['checklists as completed_checklists' => function ($q) use ($userId) {
                $q->where('user_id', $userId)->where('status', UserChecklist::STATUS_COMPLETED);
            }])
            ->orderBy('total_checklists', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($family) {
                $family->progress_percentage = $family->total_checklists > 0 
                    ? round(($family->completed_checklists / $family->total_checklists) * 100, 1) 
                    : 0;
                return $family;
            });

        return [
            'person_progress' => $personProgress,
            'family_progress' => $familyProgress,
            'top_persons' => $topPersons,
            'top_families' => $topFamilies,
        ];
    }

    protected function getSubjectTypeProgress(string $subjectType): array
    {
        $userId = Auth::id();

        $totalChecklists = UserChecklist::where('user_id', $userId)
            ->where('subject_type', $subjectType)
            ->count();

        $completedChecklists = UserChecklist::where('user_id', $userId)
            ->where('subject_type', $subjectType)
            ->where('status', UserChecklist::STATUS_COMPLETED)
            ->count();

        $inProgressChecklists = UserChecklist::where('user_id', $userId)
            ->where('subject_type', $subjectType)
            ->where('status', UserChecklist::STATUS_IN_PROGRESS)
            ->count();

        return [
            'total' => $totalChecklists,
            'completed' => $completedChecklists,
            'in_progress' => $inProgressChecklists,
            'progress_percentage' => $totalChecklists > 0 ? round(($completedChecklists / $totalChecklists) * 100, 1) : 0,
        ];
    }

    public function updatedSelectedPeriod()
    {
        // Refresh data when period changes
    }

    public function updatedSelectedSubjectType()
    {
        // Refresh data when subject type changes
    }

    public function toggleDetails()
    {
        $this->showDetails = !$this->showDetails;
    }
}