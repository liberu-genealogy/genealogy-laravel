<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResearchSpaceCollaborator extends Model
{
    use HasFactory;

    protected $table = 'research_space_collaborators';

    protected $fillable = [
        'research_space_id',
        'user_id',
        'role', // owner, admin, editor, viewer
        'permissions',
        'invited_by',
        'accepted_at',
    ];

    protected $casts = [
        'permissions' => 'array',
        'accepted_at' => 'datetime',
    ];

    public function researchSpace(): BelongsTo
    {
        return $this->belongsTo(ResearchSpace::class, 'research_space_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
