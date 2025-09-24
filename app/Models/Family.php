<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Family extends \FamilyTree365\LaravelGedcom\Models\Family
{
    use HasFactory;
    use BelongsToTenant;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get checklists associated with this family
     */
    public function checklists()
    {
        return $this->morphMany(UserChecklist::class, 'subject');
    }

    /**
     * Get active checklists for this family
     */
    public function activeChecklists()
    {
        return $this->checklists()->whereIn('status', [
            UserChecklist::STATUS_NOT_STARTED,
            UserChecklist::STATUS_IN_PROGRESS
        ]);
    }

    /**
     * Get completed checklists for this family
     */
    public function completedChecklists()
    {
        return $this->checklists()->where('status', UserChecklist::STATUS_COMPLETED);
    }

    /**
     * Get the total research progress for this family
     */
    public function getResearchProgressAttribute(): float
    {
        $totalChecklists = $this->checklists()->count();
        if ($totalChecklists === 0) {
            return 0;
        }

        $completedChecklists = $this->completedChecklists()->count();
        return round(($completedChecklists / $totalChecklists) * 100, 2);
    }

    /**
     * Check if this family has any overdue checklists
     */
    public function hasOverdueChecklists(): bool
    {
        return $this->checklists()
            ->where('due_date', '<', now())
            ->where('status', '!=', UserChecklist::STATUS_COMPLETED)
            ->exists();
    }

    /**
     * Get research summary for this family
     */
    public function getResearchSummary(): array
    {
        $checklists = $this->checklists()->with('items')->get();

        return [
            'total_checklists' => $checklists->count(),
            'completed_checklists' => $checklists->where('status', UserChecklist::STATUS_COMPLETED)->count(),
            'in_progress_checklists' => $checklists->where('status', UserChecklist::STATUS_IN_PROGRESS)->count(),
            'overdue_checklists' => $checklists->filter(fn($c) => $c->is_overdue)->count(),
            'total_items' => $checklists->sum(fn($c) => $c->items->count()),
            'completed_items' => $checklists->sum(fn($c) => $c->items->where('is_completed', true)->count()),
            'progress_percentage' => $this->research_progress,
        ];
    }
}
