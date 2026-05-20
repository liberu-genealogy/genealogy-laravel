<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class VirtualEventAttendee extends Model
{
    use HasFactory;

    protected $fillable = [
        'virtual_event_id',
        'user_id',
        'person_id',
        'guest_name',
        'guest_email',
        'rsvp_status',
        'rsvp_date',
        'rsvp_notes',
        'attended',
        'joined_at',
        'left_at',
        'duration_minutes',
        'attendance_data',
        'is_host',
        'is_moderator',
        'invitation_token',
        'invitation_sent_at',
    ];

    protected $casts = [
        'rsvp_date' => 'datetime',
        'joined_at' => 'datetime',
        'left_at' => 'datetime',
        'attendance_data' => 'array',
        'attended' => 'boolean',
        'is_host' => 'boolean',
        'is_moderator' => 'boolean',
        'invitation_sent_at' => 'datetime',
    ];

    protected $attributes = [
        'rsvp_status' => 'pending',
        'attended' => false,
        'is_host' => false,
        'is_moderator' => false,
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($attendee) {
            if (!$attendee->invitation_token) {
                $attendee->invitation_token = Str::random(32);
            }
        });
    }

    public function virtualEvent(): BelongsTo
    {
        return $this->belongsTo(VirtualEvent::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    // Scopes
    public function scopeAccepted($query)
    {
        return $query->where('rsvp_status', 'accepted');
    }

    public function scopePending($query)
    {
        return $query->where('rsvp_status', 'pending');
    }

    public function scopeDeclined($query)
    {
        return $query->where('rsvp_status', 'declined');
    }

    public function scopeAttended($query)
    {
        return $query->where('attended', true);
    }

    public function scopeHosts($query)
    {
        return $query->where('is_host', true);
    }

    public function scopeModerators($query)
    {
        return $query->where('is_moderator', true);
    }

    // Accessors
    public function getDisplayNameAttribute(): string
    {
        if ($this->user) {
            return $this->user->name;
        }

        if ($this->person) {
            return $this->person->name ?? ($this->person->givn . ' ' . $this->person->surn);
        }

        return $this->guest_name ?? 'Unknown';
    }

    public function getDisplayEmailAttribute(): string
    {
        if ($this->user) {
            return $this->user->email;
        }

        if ($this->person && $this->person->email) {
            return $this->person->email;
        }

        return $this->guest_email ?? '';
    }

    public function getAttendanceDurationAttribute(): ?string
    {
        if (!$this->attended || !$this->joined_at) {
            return null;
        }

        if ($this->duration_minutes) {
            $hours = intval($this->duration_minutes / 60);
            $minutes = $this->duration_minutes % 60;

            if ($hours > 0) {
                return "{$hours}h {$minutes}m";
            }

            return "{$minutes}m";
        }

        if ($this->left_at) {
            $duration = $this->joined_at->diffInMinutes($this->left_at);
            $hours = intval($duration / 60);
            $minutes = $duration % 60;

            if ($hours > 0) {
                return "{$hours}h {$minutes}m";
            }

            return "{$minutes}m";
        }

        return 'Still in meeting';
    }

    public function getRsvpStatusColorAttribute(): string
    {
        return match ($this->rsvp_status) {
            'accepted' => 'success',
            'declined' => 'danger',
            'maybe' => 'warning',
            default => 'gray',
        };
    }

    public function getRsvpStatusLabelAttribute(): string
    {
        return match ($this->rsvp_status) {
            'accepted' => 'Accepted',
            'declined' => 'Declined',
            'maybe' => 'Maybe',
            default => 'Pending',
        };
    }

    // Helper methods
    public function accept(string $notes = null): void
    {
        $this->update([
            'rsvp_status' => 'accepted',
            'rsvp_date' => now(),
            'rsvp_notes' => $notes,
        ]);
    }

    public function decline(string $notes = null): void
    {
        $this->update([
            'rsvp_status' => 'declined',
            'rsvp_date' => now(),
            'rsvp_notes' => $notes,
        ]);
    }

    public function maybe(string $notes = null): void
    {
        $this->update([
            'rsvp_status' => 'maybe',
            'rsvp_date' => now(),
            'rsvp_notes' => $notes,
        ]);
    }

    public function markAsAttended(array $attendanceData = []): void
    {
        $this->update([
            'attended' => true,
            'joined_at' => $attendanceData['joined_at'] ?? now(),
            'left_at' => $attendanceData['left_at'] ?? null,
            'duration_minutes' => $attendanceData['duration_minutes'] ?? null,
            'attendance_data' => $attendanceData,
        ]);
    }

    public function canRsvp(): bool
    {
        return $this->virtualEvent->status === 'published' && 
               $this->virtualEvent->start_time > now();
    }

    public function canJoin(): bool
    {
        return $this->rsvp_status === 'accepted' && 
               $this->virtualEvent->canJoin();
    }
}