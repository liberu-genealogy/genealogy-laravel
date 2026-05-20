<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Family extends \FamilyTree365\LaravelGedcom\Models\Family
{
    use HasFactory;
    use BelongsToTenant;

    /**
     * Unset the parent's public property declarations that shadow Eloquent's
     * dynamic attribute/relationship system.  Once unset on the instance,
     * PHP falls through to __get(), which Eloquent uses to resolve attributes
     * and relationships from $this->attributes / $this->relations.
     */
    public function __construct(array $attributes = [])
    {
        // Unset inherited public properties that would shadow __get()
        unset($this->id, $this->husband, $this->wife);
        parent::__construct($attributes);
    }

    /**
     * Include team_id (multi-tenancy) alongside the vendor's base fillable
     * list so that the GEDCOM importer can mass-assign it.
     */
    protected $fillable = [
        'description',
        'is_active',
        'type_id',
        'husband_id',
        'wife_id',
        'chan',
        'nchi',
        'rin',
        'team_id',
    ];

    /**
     * Convert an invalid type_id of 0 (hardcoded by the vendor GEDCOM parser)
     * to null so it satisfies the nullable FK constraint on the types table.
     * MySQL rejects 0 because no types record exists with id = 0.
     */
    public function setTypeIdAttribute(mixed $value): void
    {
        $this->attributes['type_id'] = ($value === 0 || $value === '0') ? null : $value;
    }

    /**
     * Override the vendor's husband relationship to use App\Models\Person (people table).
     */
    public function husband(): HasOne
    {
        return $this->hasOne(Person::class, 'id', 'husband_id');
    }

    /**
     * Override the vendor's wife relationship to use App\Models\Person (people table).
     */
    public function wife(): HasOne
    {
        return $this->hasOne(Person::class, 'id', 'wife_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get checklists associated with this family
     */
    public function checklists()
    {
        return $this->morphMany(UserChecklist::class, 'subject');
    }

    /**
     * Get active checklists for this family
     */
    public function activeChecklists()
    {
        return $this->checklists()->whereIn('status', [
            UserChecklist::STATUS_NOT_STARTED,
            UserChecklist::STATUS_IN_PROGRESS
        ]);
    }

    /**
     * Get completed checklists for this family
     */
    public function completedChecklists()
    {
        return $this->checklists()->where('status', UserChecklist::STATUS_COMPLETED);
    }

    /**
     * Get the total research progress for this family
     */
    public function getResearchProgressAttribute(): float
    {
        $totalChecklists = $this->checklists()->count();
        if ($totalChecklists === 0) {
            return 0;
        }

        $completedChecklists = $this->completedChecklists()->count();
        return round(($completedChecklists / $totalChecklists) * 100, 2);
    }

    /**
     * Check if this family has any overdue checklists
     */
    public function hasOverdueChecklists(): bool
    {
        return $this->checklists()
            ->where('due_date', '<', now())
            ->where('status', '!=', UserChecklist::STATUS_COMPLETED)
            ->exists();
    }

    /**
     * Get research summary for this family
     */
    public function getResearchSummary(): array
    {
        $checklists = $this->checklists()->with('items')->get();

        return [
            'total_checklists' => $checklists->count(),
            'completed_checklists' => $checklists->where('status', UserChecklist::STATUS_COMPLETED)->count(),
            'in_progress_checklists' => $checklists->where('status', UserChecklist::STATUS_IN_PROGRESS)->count(),
            'overdue_checklists' => $checklists->filter(fn($c) => $c->is_overdue)->count(),
            'total_items' => $checklists->sum(fn($c) => $c->items->count()),
            'completed_items' => $checklists->sum(fn($c) => $c->items->where('is_completed', true)->count()),
            'progress_percentage' => $this->research_progress,
        ];
    }
}
