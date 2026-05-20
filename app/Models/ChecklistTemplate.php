<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChecklistTemplate extends Model
{
    use HasFactory, BelongsToTenant, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'category',
        'is_public',
        'is_default',
        'created_by',
        'tags',
        'difficulty_level',
        'estimated_time',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'is_default' => 'boolean',
        'tags' => 'array',
        'estimated_time' => 'integer', // in minutes
    ];

    /**
     * Get the template items for this checklist template
     */
    public function templateItems(): HasMany
    {
        return $this->hasMany(ChecklistTemplateItem::class)->orderBy('order');
    }

    /**
     * Get the user who created this template
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get user checklists created from this template
     */
    public function userChecklists(): HasMany
    {
        return $this->hasMany(UserChecklist::class);
    }

    /**
     * Get the completion percentage for this template
     */
    public function getCompletionPercentageAttribute(): float
    {
        $totalItems = $this->templateItems()->count();
        if ($totalItems === 0) {
            return 0;
        }

        $completedItems = $this->templateItems()
            ->whereHas('userChecklistItems', function ($query) {
                $query->where('is_completed', true);
            })
            ->count();

        return round(($completedItems / $totalItems) * 100, 2);
    }

    /**
     * Scope for public templates
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope for default templates
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Scope for templates by category
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }
}