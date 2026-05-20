<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserChecklist extends Model
{
    use HasFactory, BelongsToTenant, SoftDeletes;

    protected $fillable = [
        'user_id',
        'checklist_template_id',
        'name',
        'description',
        'subject_type',
        'subject_id',
        'status',
        'started_at',
        'completed_at',
        'notes',
        'priority',
        'due_date',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'due_date' => 'date',
    ];

    const STATUS_NOT_STARTED = 'not_started';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_ON_HOLD = 'on_hold';

    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_URGENT = 'urgent';

    /**
     * Get the user who owns this checklist
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the template this checklist was created from
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(ChecklistTemplate::class, 'checklist_template_id');
    }

    /**
     * Get the subject (Person, Family, etc.) this checklist is for
     */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the checklist items for this user checklist
     */
    public function items(): HasMany
    {
        return $this->hasMany(UserChecklistItem::class)->orderBy('order');
    }

    /**
     * Get completed items
     */
    public function completedItems(): HasMany
    {
        return $this->hasMany(UserChecklistItem::class)->where('is_completed', true);
    }

    /**
     * Get pending items
     */
    public function pendingItems(): HasMany
    {
        return $this->hasMany(UserChecklistItem::class)->where('is_completed', false);
    }

    /**
     * Get the completion percentage
     */
    public function getCompletionPercentageAttribute(): float
    {
        $totalItems = $this->items()->count();
        if ($totalItems === 0) {
            return 0;
        }

        $completedItems = $this->completedItems()->count();
        return round(($completedItems / $totalItems) * 100, 2);
    }

    /**
     * Get the total estimated time in minutes
     */
    public function getTotalEstimatedTimeAttribute(): int
    {
        return $this->items()->sum('estimated_time') ?? 0;
    }

    /**
     * Get the remaining estimated time in minutes
     */
    public function getRemainingEstimatedTimeAttribute(): int
    {
        return $this->pendingItems()->sum('estimated_time') ?? 0;
    }

    /**
     * Check if the checklist is overdue
     */
    public function getIsOverdueAttribute(): bool
    {
        return $this->due_date && $this->due_date->isPast() && $this->status !== self::STATUS_COMPLETED;
    }

    /**
     * Mark checklist as started
     */
    public function markAsStarted(): void
    {
        $this->update([
            'status' => self::STATUS_IN_PROGRESS,
            'started_at' => now(),
        ]);
    }

    /**
     * Mark checklist as completed
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);
    }

    /**
     * Scope for active checklists
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', [self::STATUS_NOT_STARTED, self::STATUS_IN_PROGRESS]);
    }

    /**
     * Scope for completed checklists
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Scope for overdue checklists
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                    ->where('status', '!=', self::STATUS_COMPLETED);
    }

    /**
     * Scope by priority
     */
    public function scopeByPriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }
}