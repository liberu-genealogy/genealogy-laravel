<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Source extends \FamilyTree365\LaravelGedcom\Models\Source
{
    use HasFactory;
    use BelongsToTenant;

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'archive_metadata' => 'array',
    ];

    /**
     * Get the record type that this source belongs to.
     */
    public function recordType(): BelongsTo
    {
        return $this->belongsTo(RecordType::class);
    }

    /**
     * Check if this source has a specific record type category.
     */
    public function hasCategory(string $category): bool
    {
        return $this->recordType?->category === $category;
    }

    /**
     * Check if this is a newspaper source.
     */
    public function isNewspaper(): bool
    {
        return $this->hasCategory('newspaper');
    }

    /**
     * Check if this is a census source.
     */
    public function isCensus(): bool
    {
        return $this->hasCategory('census');
    }

    /**
     * Check if this is a parish record source.
     */
    public function isParish(): bool
    {
        return $this->hasCategory('parish');
    }
}
