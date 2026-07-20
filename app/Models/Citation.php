<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Citation extends Model
{
    use BelongsToTenant;
    use HasFactory;

    #[\Override]
    protected $fillable = ['name', 'description', 'repository_id', 'volume', 'page', 'is_active', 'confidence', 'source_id'];

    #[\Override]
    protected $attributes = ['is_active' => false];

    /**
     * The source this citation references. citations.source_id is a single FK
     * (Source hasMany Citation), so this is a belongsTo — not the belongsToMany
     * it used to be, which queried a citation_source pivot no migration creates.
     */
    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    #[\Override]
    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }
}
