<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DnaMatching extends Model
{
    use HasFactory;
    use BelongsToTenant;

    protected $fillable = [
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
