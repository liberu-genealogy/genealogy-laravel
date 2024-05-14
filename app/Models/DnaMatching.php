<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DnaMatching extends Model
{
    use HasFactory;

    protected $fillable = [
        'file1',
        'file2',
        'image',
        'total_shared_cm',
        'largest_cm_segment',
        'match_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
