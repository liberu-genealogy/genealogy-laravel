<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'name',
        'description',
        'icon',
        'category',
        'points',
        'requirements',
        'badge_color',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'requirements' => 'array',
        'is_active' => 'boolean',
        'points' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Get all user achievements for this achievement
     */
    public function userAchievements(): HasMany
    {
        return $this->hasMany(UserAchievement::class);
    }

    /**
     * Get all user progress for this achievement
     */
    public function userProgress(): HasMany
    {
        return $this->hasMany(UserProgress::class);
    }

    /**
     * Scope to get only active achievements
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get achievements by category
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope to order by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Check if a user has unlocked this achievement
     */
    public function isUnlockedBy(User $user): bool
    {
        return $this->userAchievements()
            ->where('user_id', $user->id)
            ->exists();
    }

    /**
     * Get the progress for a specific user
     */
    public function getProgressFor(User $user): ?UserProgress
    {
        return $this->userProgress()
            ->where('user_id', $user->id)
            ->first();
    }

    /**
     * Get the requirement value for a specific key
     */
    public function getRequirement(string $key, $default = null)
    {
        return $this->requirements[$key] ?? $default;
    }

    /**
     * Check if this achievement has a specific requirement
     */
    public function hasRequirement(string $key): bool
    {
        return isset($this->requirements[$key]);
    }

    /**
     * Get the badge HTML for this achievement
     */
    public function getBadgeHtml(bool $unlocked = true): string
    {
        $opacity = $unlocked ? 'opacity-100' : 'opacity-50';
        $bgColor = $this->getBadgeColorClass();

        return "<div class=\"achievement-badge {$opacity} {$bgColor} rounded-lg p-3 text-center\">
                    <div class=\"text-2xl mb-1\">{$this->icon}</div>
                    <div class=\"text-sm font-medium text-white\">{$this->name}</div>
                    <div class=\"text-xs text-white/80\">{$this->points} pts</div>
                </div>";
    }

    /**
     * Get the CSS class for the badge color
     */
    private function getBadgeColorClass(): string
    {
        return match($this->badge_color) {
            'gold' => 'bg-gradient-to-br from-yellow-400 to-yellow-600',
            'silver' => 'bg-gradient-to-br from-gray-300 to-gray-500',
            'bronze' => 'bg-gradient-to-br from-orange-400 to-orange-600',
            'blue' => 'bg-gradient-to-br from-blue-400 to-blue-600',
            'green' => 'bg-gradient-to-br from-green-400 to-green-600',
            'purple' => 'bg-gradient-to-br from-purple-400 to-purple-600',
            'red' => 'bg-gradient-to-br from-red-400 to-red-600',
            default => 'bg-gradient-to-br from-gray-400 to-gray-600',
        };
    }
}