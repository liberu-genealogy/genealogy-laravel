<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class VirtualEvent extends Model
{
    use HasFactory, BelongsToTenant, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'start_time',
        'end_time',
        'timezone',
        'status',
        'platform',
        'meeting_id',
        'meeting_password',
        'meeting_url',
        'join_url',
        'platform_data',
        'max_attendees',
        'require_rsvp',
        'allow_guests',
        'instructions',
        'host_email',
        'created_by',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'platform_data' => 'array',
        'require_rsvp' => 'boolean',
        'allow_guests' => 'boolean',
    ];

    protected $attributes = [
        'status' => 'draft',
        'platform' => 'zoom',
        'timezone' => 'UTC',
        'require_rsvp' => true,
        'allow_guests' => false,
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function attendees(): HasMany
    {
        return $this->hasMany(VirtualEventAttendee::class);
    }

    public function acceptedAttendees(): HasMany
    {
        return $this->hasMany(VirtualEventAttendee::class)->where('rsvp_status', 'accepted');
    }

    public function pendingAttendees(): HasMany
    {
        return $this->hasMany(VirtualEventAttendee::class)->where('rsvp_status', 'pending');
    }

    public function actualAttendees(): HasMany
    {
        return $this->hasMany(VirtualEventAttendee::class)->where('attended', true);
    }

    public function hosts(): HasMany
    {
        return $this->hasMany(VirtualEventAttendee::class)->where('is_host', true);
    }

    public function moderators(): HasMany
    {
        return $this->hasMany(VirtualEventAttendee::class)->where('is_moderator', true);
    }

    // Scopes
    public function scopeUpcoming($query)
    {
        return $query->where('start_time', '>', now());
    }

    public function scopeActive($query)
    {
        return $query->where('start_time', '<=', now())
                    ->where('end_time', '>=', now());
    }

    public function scopePast($query)
    {
        return $query->where('end_time', '<', now());
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    // Accessors & Mutators
    public function getIsUpcomingAttribute(): bool
    {
        return $this->start_time > now();
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->start_time <= now() && $this->end_time >= now();
    }

    public function getIsPastAttribute(): bool
    {
        return $this->end_time < now();
    }

    public function getDurationInMinutesAttribute(): int
    {
        return $this->start_time->diffInMinutes($this->end_time);
    }

    public function getAttendeeCountAttribute(): int
    {
        return $this->attendees()->count();
    }

    public function getAcceptedCountAttribute(): int
    {
        return $this->acceptedAttendees()->count();
    }

    public function getActualAttendeeCountAttribute(): int
    {
        return $this->actualAttendees()->count();
    }

    public function getFormattedStartTimeAttribute(): string
    {
        return $this->start_time->setTimezone($this->timezone)->format('M j, Y g:i A T');
    }

    public function getFormattedEndTimeAttribute(): string
    {
        return $this->end_time->setTimezone($this->timezone)->format('M j, Y g:i A T');
    }

    // Helper methods
    public function canJoin(): bool
    {
        return $this->status === 'published' && 
               $this->start_time <= now()->addMinutes(15) && // Allow joining 15 minutes early
               $this->end_time >= now();
    }

    public function isAtCapacity(): bool
    {
        return $this->max_attendees && $this->accepted_count >= $this->max_attendees;
    }

    public function hasUser(User $user): bool
    {
        return $this->attendees()->where('user_id', $user->id)->exists();
    }

    public function hasPerson(Person $person): bool
    {
        return $this->attendees()->where('person_id', $person->id)->exists();
    }

    public function getUserAttendee(User $user): ?VirtualEventAttendee
    {
        return $this->attendees()->where('user_id', $user->id)->first();
    }

    public function getPersonAttendee(Person $person): ?VirtualEventAttendee
    {
        return $this->attendees()->where('person_id', $person->id)->first();
    }
}