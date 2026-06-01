<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AIMatchFeedback extends Model
{
    #[\Override]
    protected $table = 'ai_match_feedbacks';

    #[\Override]
    protected $fillable = [
        'suggested_match_id',
        'user_id',
        'action',
        'payload',
    ];

    #[\Override]
    protected $casts = [
        'payload' => 'array',
    ];

    public function suggestedMatch()
    {
        return $this->belongsTo(AISuggestedMatch::class, 'suggested_match_id');
    }
}
