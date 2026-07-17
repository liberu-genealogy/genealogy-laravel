<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Source extends \FamilyTree365\LaravelGedcom\Models\Source
{
    use BelongsToTenant;
    use HasFactory;

    /**
     * The attributes that should be cast.
     */
    #[\Override]
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
     * Citations that cite this source — the evidence records carrying
     * confidence/page/volume.
     *
     * Overrides the base package relation, which resolves Citation to the
     * untenanted FamilyTree365\LaravelGedcom\Models\Citation. We need
     * App\Models\Citation so BelongsToTenant scopes reads and stamps team_id
     * on the evidence link.
     */
    #[\Override]
    public function citations(): HasMany
    {
        return $this->hasMany(Citation::class);
    }

    /**
     * Every GEDCOM SOUR reference citing this source, whatever it evidences.
     * `sour_id` carries no FK constraint.
     */
    public function sourceRefs(): HasMany
    {
        return $this->hasMany(SourceRef::class, 'sour_id');
    }

    /**
     * The people this source evidences. `source_ref` acts as the pivot — gid is
     * only people.id while group is 'indi', hence the pivot filter.
     *
     * Read-side only: attaching through this relation would write neither `group`
     * nor `team_id`. Create person-level refs via Person::sourceRefs().
     */
    public function people(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'source_ref', 'sour_id', 'gid')
            ->wherePivot('group', SourceRef::GROUP_INDI);
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
