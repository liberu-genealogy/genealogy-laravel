<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DuplicateMatch extends Model
{
    use BelongsToTenant;

    #[\Override]
    protected $fillable = [
        'team_id',
        'primary_person_id',
        'duplicate_person_id',
        'confidence_score',
        'match_data',
        'status',
        'reviewed_by',
        'reviewed_at',
    ];

    #[\Override]
    protected $casts = [
        'match_data' => 'array',
        'confidence_score' => 'decimal:4',
        'reviewed_at' => 'datetime',
    ];

    public function primaryPerson(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'primary_person_id');
    }

    public function duplicatePerson(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'duplicate_person_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function markReviewed(string $status, ?User $user = null): void
    {
        $this->status = $status;
        $this->reviewed_at = now();
        $this->reviewed_by = $user instanceof User ? $user->id : null;
        $this->save();
    }
}
