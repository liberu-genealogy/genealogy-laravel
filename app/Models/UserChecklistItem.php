<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserChecklistItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_checklist_id',
        'checklist_template_item_id',
        'title',
        'description',
        'order',
        'is_completed',
        'completed_at',
        'notes',
        'estimated_time',
        'actual_time',
        'resources',
        'tips',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
        'estimated_time' => 'integer', // in minutes
        'actual_time' => 'integer', // in minutes
        'resources' => 'array',
        'tips' => 'array',
    ];

    /**
     * Get the user checklist this item belongs to
     */
    public function userChecklist(): BelongsTo
    {
        return $this->belongsTo(UserChecklist::class);
    }

    /**
     * Get the template item this was created from
     */
    public function templateItem(): BelongsTo
    {
        return $this->belongsTo(ChecklistTemplateItem::class, 'checklist_template_item_id');
    }

    /**
     * Mark item as completed
     */
    public function markAsCompleted(int $actualTime = null): void
    {
        $this->update([
            'is_completed' => true,
            'completed_at' => now(),
            'actual_time' => $actualTime,
        ]);

        // Check if all items in the checklist are completed
        $userChecklist = $this->userChecklist;
        if ($userChecklist->items()->where('is_completed', false)->count() === 0) {
            $userChecklist->markAsCompleted();
        } elseif ($userChecklist->status === UserChecklist::STATUS_NOT_STARTED) {
            $userChecklist->markAsStarted();
        }
    }

    /**
     * Mark item as incomplete
     */
    public function markAsIncomplete(): void
    {
        $this->update([
            'is_completed' => false,
            'completed_at' => null,
            'actual_time' => null,
        ]);

        // Update checklist status if needed
        $userChecklist = $this->userChecklist;
        if ($userChecklist->status === UserChecklist::STATUS_COMPLETED) {
            $userChecklist->update(['status' => UserChecklist::STATUS_IN_PROGRESS]);
        }
    }

    /**
     * Scope for completed items
     */
    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    /**
     * Scope for pending items
     */
    public function scopePending($query)
    {
        return $query->where('is_completed', false);
    }
}