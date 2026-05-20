<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAchievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'achievement_id',
        'unlocked_at',
        'progress_data',
    ];

    protected $casts = [
        'unlocked_at' => 'datetime',
        'progress_data' => 'array',
    ];

    /**
     * Get the user that owns this achievement
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
     * Scope to get recent achievements
     */
    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('unlocked_at', '>=', now()->subDays($days));
    }

    /**
     * Scope to get achievements by category
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->whereHas('achievement', function ($q) use ($category) {
            $q->where('category', $category);
        });
    }

    /**
     * Get the time since this achievement was unlocked
     */
    public function getTimeSinceUnlocked(): string
    {
        return $this->unlocked_at->diffForHumans();
    }

    /**
     * Get progress data for a specific key
     */
    public function getProgressData(string $key, $default = null)
    {
        return $this->progress_data[$key] ?? $default;
    }
}