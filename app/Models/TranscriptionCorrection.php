<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TranscriptionCorrection extends Model
{
    protected $fillable = [
        'document_transcription_id',
        'user_id',
        'original_text',
        'corrected_text',
        'position_start',
        'position_end',
        'correction_metadata',
    ];

    protected $casts = [
        'correction_metadata' => 'array',
    ];

    public function documentTranscription(): BelongsTo
    {
        return $this->belongsTo(DocumentTranscription::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
