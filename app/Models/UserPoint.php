<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class UserPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'activity_type',
        'points',
        'description',
        'metadata',
        'related_model_id',
        'related_model_type',
    ];

    protected $casts = [
        'metadata' => 'array',
        'points' => 'integer',
    ];

    /**
     * Get the user that earned these points
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the related model (polymorphic)
     */
    public function relatedModel(): MorphTo
    {
        return $this->morphTo('related_model', 'related_model_type', 'related_model_id');
    }

    /**
     * Scope to get points by activity type
     */
    public function scopeByActivity($query, string $activityType)
    {
        return $query->where('activity_type', $activityType);
    }

    /**
     * Scope to get recent points
     */
    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope to get points for a specific date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Get metadata for a specific key
     */
    public function getMetadata(string $key, $default = null)
    {
        return $this->metadata[$key] ?? $default;
    }

    /**
     * Get a formatted description of the point activity
     */
    public function getFormattedDescription(): string
    {
        if ($this->description) {
            return $this->description;
        }

        return match($this->activity_type) {
            'person_added' => 'Added a new person to the family tree',
            'family_created' => 'Created a new family relationship',
            'event_added' => 'Added an event to a person\'s timeline',
            'photo_uploaded' => 'Uploaded a photo',
            'achievement_unlocked' => 'Unlocked an achievement',
            'profile_completed' => 'Completed profile information',
            'research_note_added' => 'Added a research note',
            'source_added' => 'Added a source citation',
            default => 'Earned points for genealogy activity',
        };
    }

    /**
     * Get the icon for this activity type
     */
    public function getActivityIcon(): string
    {
        return match($this->activity_type) {
            'person_added' => '👤',
            'family_created' => '👨‍👩‍👧‍👦',
            'event_added' => '📅',
            'photo_uploaded' => '📸',
            'achievement_unlocked' => '🏆',
            'profile_completed' => '✅',
            'research_note_added' => '📝',
            'source_added' => '📚',
            default => '⭐',
        };
    }
}