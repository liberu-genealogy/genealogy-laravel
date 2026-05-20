<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'achievement_id',
        'current_progress',
        'target_progress',
        'progress_data',
        'started_at',
        'last_updated_at',
    ];

    protected $casts = [
        'current_progress' => 'integer',
        'target_progress' => 'integer',
        'progress_data' => 'array',
        'started_at' => 'datetime',
        'last_updated_at' => 'datetime',
    ];

    /**
     * Get the user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the achievement
     */
    public function achievement(): BelongsTo
    {
        return $this->belongsTo(Achievement::class);
    }

    /**
     * Get the progress percentage
     */
    public function getProgressPercentage(): float
    {
        if ($this->target_progress <= 0) {
            return 0;
        }

        return min(100, ($this->current_progress / $this->target_progress) * 100);
    }

    /**
     * Check if the progress is complete
     */
    public function isComplete(): bool
    {
        return $this->current_progress >= $this->target_progress;
    }

    /**
     * Get remaining progress needed
     */
    public function getRemainingProgress(): int
    {
        return max(0, $this->target_progress - $this->current_progress);
    }

    /**
     * Increment the progress
     */
    public function incrementProgress(int $amount = 1, array $additionalData = []): void
    {
        $this->current_progress += $amount;
        $this->last_updated_at = now();

        if (!empty($additionalData)) {
            $currentData = $this->progress_data ?? [];
            $this->progress_data = array_merge($currentData, $additionalData);
        }

        $this->save();
    }

    /**
     * Set the progress to a specific value
     */
    public function setProgress(int $progress, array $additionalData = []): void
    {
        $this->current_progress = $progress;
        $this->last_updated_at = now();

        if (!empty($additionalData)) {
            $currentData = $this->progress_data ?? [];
            $this->progress_data = array_merge($currentData, $additionalData);
        }

        $this->save();
    }

    /**
     * Get progress data for a specific key
     */
    public function getProgressData(string $key, $default = null)
    {
        return $this->progress_data[$key] ?? $default;
    }

    /**
     * Get a formatted progress string
     */
    public function getFormattedProgress(): string
    {
        return "{$this->current_progress} / {$this->target_progress}";
    }

    /**
     * Scope to get incomplete progress
     */
    public function scopeIncomplete($query)
    {
        return $query->whereRaw('current_progress < target_progress');
    }

    /**
     * Scope to get complete progress
     */
    public function scopeComplete($query)
    {
        return $query->whereRaw('current_progress >= target_progress');
    }

    /**
     * Scope to get recently updated progress
     */
    public function scopeRecentlyUpdated($query, int $days = 7)
    {
        return $query->where('last_updated_at', '>=', now()->subDays($days));
    }
}