<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Team;

class AISuggestedMatch extends Model
{
    #[\Override]
    protected $table = 'ai_suggested_matches';

    #[\Override]
    protected $fillable = [
        'team_id',
        'local_person_id',
        'provider',
        'external_record_id',
        'candidate_data',
        'confidence',
        'status',
    ];

    #[\Override]
    protected $casts = [
        'candidate_data' => 'array',
        'confidence' => 'float',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(AIMatchFeedback::class, 'suggested_match_id');
    }
}
