<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AISuggestedMatch extends Model
{
    protected $table = 'ai_suggested_matches';

    protected $fillable = [
        'local_person_id',
        'provider',
        'external_record_id',
        'candidate_data',
        'confidence',
        'status',
    ];

    protected $casts = [
        'candidate_data' => 'array',
        'confidence' => 'float',
    ];

    public function feedbacks()
    {
        return $this->hasMany(AIMatchFeedback::class, 'suggested_match_id');
    }
}
