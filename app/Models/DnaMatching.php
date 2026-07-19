<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DnaMatching extends Model
{
    use BelongsToTenant;
    use HasFactory;

    #[\Override]
    protected $fillable = [
        // Writers that run unauthenticated (console commands, queued jobs) must
        // set this explicitly: BelongsToTenant's creating hook reads
        // auth()->user() and bails when there is none. Without team_id here,
        // mass assignment silently dropped it and every such row landed with a
        // null tenant — invisible in the tenant-scoped App panel, visible only
        // to Admin, which bypasses global scopes.
        'team_id',
        'user_id',
        'file1',
        'file2',
        'image',
        'total_shared_cm',
        'largest_cm_segment',
        'match_id',
        'match_name',
        'confidence_level',
        'predicted_relationship',
        'shared_segments_count',
        'match_quality_score',
        'detailed_report',
        'chromosome_breakdown',
        'analysis_date',
    ];

    #[\Override]
    protected $casts = [
        'detailed_report' => 'array',
        'chromosome_breakdown' => 'array',
        'analysis_date' => 'datetime',
        'confidence_level' => 'float',
        'match_quality_score' => 'float',
        'total_shared_cm' => 'float',
        'largest_cm_segment' => 'float',
        'shared_segments_count' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
