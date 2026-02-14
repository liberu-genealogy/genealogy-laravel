<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentTranscription extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'team_id',
        'user_id',
        'original_filename',
        'document_path',
        'raw_transcription',
        'corrected_transcription',
        'metadata',
        'status',
        'processed_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'processed_at' => 'datetime',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function corrections(): HasMany
    {
        return $this->hasMany(TranscriptionCorrection::class);
    }

    /**
     * Get the current transcription (corrected if available, otherwise raw)
     */
    public function getCurrentTranscription(): ?string
    {
        return $this->corrected_transcription ?? $this->raw_transcription;
    }

    /**
     * Check if transcription has been corrected
     */
    public function hasCorrections(): bool
    {
        return !empty($this->corrected_transcription) || $this->corrections()->exists();
    }

    /**
     * Get confidence score from metadata
     */
    public function getConfidenceScore(): ?float
    {
        return $this->metadata['confidence'] ?? null;
    }
}
