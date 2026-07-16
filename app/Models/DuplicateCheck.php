<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DuplicateCheck extends Model
{
    use BelongsToTenant;
    use HasFactory;

    #[\Override]
    protected $fillable = [
        'team_id',
        'user_id',
        'results',
        'duplicates_found',
        'status',
        'completed_at',
    ];

    #[\Override]
    protected $casts = [
        'results' => 'array',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function hasFailed(): bool
    {
        return $this->status === 'failed';
    }
}
