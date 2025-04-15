<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Citation extends Model
{
    use HasFactory;
    use BelongsToTenant;

    protected $fillable = ['name', 'description', 'repository_id', 'volume', 'page', 'is_active', 'confidence', 'source_id'];

    protected $attributes = ['is_active' => false];

    public function sources()
    {
        return $this->belongsToMany(Source::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }
}
