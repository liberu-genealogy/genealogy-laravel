<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PersonPhoto extends Model
{
    use HasFactory;
    use BelongsToTenant;

    protected $fillable = [
        'person_id',
        'team_id',
        'file_path',
        'file_name',
        'mime_type',
        'file_size',
        'width',
        'height',
        'description',
        'is_analyzed',
        'analyzed_at',
    ];

    protected $casts = [
        'is_analyzed' => 'boolean',
        'analyzed_at' => 'datetime',
        'file_size' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function tags(): HasMany
    {
        return $this->hasMany(PhotoTag::class, 'photo_id');
    }

    public function confirmedTags(): HasMany
    {
        return $this->hasMany(PhotoTag::class, 'photo_id')->where('status', 'confirmed');
    }

    public function pendingTags(): HasMany
    {
        return $this->hasMany(PhotoTag::class, 'photo_id')->where('status', 'pending');
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }
}
