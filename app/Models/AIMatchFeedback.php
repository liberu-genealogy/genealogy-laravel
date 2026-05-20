<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AIMatchFeedback extends Model
{
    protected $table = 'ai_match_feedbacks';

    protected $fillable = [
        'suggested_match_id',
        'user_id',
        'action',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public function suggestedMatch()
    {
        return $this->belongsTo(AISuggestedMatch::class, 'suggested_match_id');
    }
}
