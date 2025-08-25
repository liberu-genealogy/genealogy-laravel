<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SmartMatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'person_id',
        'external_tree_id',
        'external_person_id',
        'match_source',
        'match_data',
        'confidence_score',
        'status',
        'reviewed_at',
    ];

    protected $casts = [
        'match_data' => 'array',
        'confidence_score' => 'decimal:2',
        'reviewed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isReviewed(): bool
    {
        return $this->status === 'reviewed';
    }

    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function getConfidencePercentageAttribute(): string
    {
        return number_format($this->confidence_score * 100, 1) . '%';
    }

    public function getConfidenceLevelAttribute(): string
    {
        $score = $this->confidence_score;
        
        if ($score >= 0.9) return 'Very High';
        if ($score >= 0.8) return 'High';
        if ($score >= 0.7) return 'Medium';
        if ($score >= 0.6) return 'Low';
        
        return 'Very Low';
    }
}