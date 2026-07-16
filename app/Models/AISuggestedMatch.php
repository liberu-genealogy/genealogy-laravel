<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class AISuggestedMatch extends Model
{
    use BelongsToTenant;

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

    public function feedbacks()
    {
        return $this->hasMany(AIMatchFeedback::class, 'suggested_match_id');
    }
}
