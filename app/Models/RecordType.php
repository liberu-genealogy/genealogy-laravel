<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RecordType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'description',
        'metadata_schema',
        'icon',
        'color',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'metadata_schema' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the sources that belong to this record type.
     */
    public function sources(): HasMany
    {
        return $this->hasMany(Source::class);
    }

    /**
     * Get the smart matches that belong to this record type.
     */
    public function smartMatches(): HasMany
    {
        return $this->hasMany(SmartMatch::class);
    }

    /**
     * Scope to get only active record types.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get record types by category.
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get ordered record types.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Check if this record type is for newspapers.
     */
    public function isNewspaper(): bool
    {
        return $this->category === 'newspaper';
    }

    /**
     * Check if this record type is for census records.
     */
    public function isCensus(): bool
    {
        return $this->category === 'census';
    }

    /**
     * Check if this record type is for parish records.
     */
    public function isParish(): bool
    {
        return $this->category === 'parish';
    }

    /**
     * Check if this record type is for electoral records.
     */
    public function isElectoral(): bool
    {
        return $this->category === 'electoral';
    }

    /**
     * Get the FindMyPast-specific record types.
     */
    public static function getFindMyPastTypes(): array
    {
        return [
            'newspaper',
            'parish',
            'census',
            'electoral',
            '1939_register',
            'military',
            'probate',
            'gro_index',
        ];
    }
}
