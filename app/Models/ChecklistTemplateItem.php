<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChecklistTemplateItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'checklist_template_id',
        'title',
        'description',
        'order',
        'category',
        'is_required',
        'estimated_time',
        'resources',
        'tips',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'estimated_time' => 'integer', // in minutes
        'resources' => 'array',
        'tips' => 'array',
    ];

    /**
     * Get the checklist template this item belongs to
     */
    public function checklistTemplate(): BelongsTo
    {
        return $this->belongsTo(ChecklistTemplate::class);
    }

    /**
     * Get user checklist items created from this template item
     */
    public function userChecklistItems(): HasMany
    {
        return $this->hasMany(UserChecklistItem::class);
    }

    /**
     * Scope for required items
     */
    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }

    /**
     * Scope for items by category
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }
}