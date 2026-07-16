<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResearchSpace extends Model
{
    use BelongsToTenant, HasFactory, SoftDeletes;

    #[\Override]
    protected $fillable = [
        'name',
        'slug',
        'description',
        'owner_id',
        'is_private',
        'settings',
        'created_by',
        'team_id',
    ];

    #[\Override]
    protected $casts = [
        'is_private' => 'boolean',
        'settings' => 'array',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function collaborators(): HasMany
    {
        return $this->hasMany(ResearchSpaceCollaborator::class);
    }
}
