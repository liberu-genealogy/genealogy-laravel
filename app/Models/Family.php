<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Family extends \FamilyTree365\LaravelGedcom\Models\Family
{
    use BelongsToTenant;
    use HasFactory;

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
    #[\Override]
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
     * Override the vendor's events relationship to use App\Models\FamilyEvent.
     *
     * The vendor's FamilyEvent cannot boot at all: its boot() calls
     * static::observe(), which re-enters bootIfNotBooted and throws "may not be
     * called on model ... while it is being booted". App\Models\FamilyEvent exists
     * precisely to work around that (it moves observe() into booted()) and adds
     * BelongsToTenant. Inheriting the vendor relation resolved FamilyEvent in the
     * vendor namespace, so every $family->events() call was fatal — and untenanted
     * had it worked. Person::events() only works because Person overrides it the
     * same way; Family never did.
     */
    #[\Override]
    public function events(): HasMany
    {
        return $this->hasMany(FamilyEvent::class);
    }

    /**
     * Override the vendor's husband relationship to use App\Models\Person (people table).
     */
    #[\Override]
    public function husband(): HasOne
    {
        return $this->hasOne(Person::class, 'id', 'husband_id');
    }

    /**
     * Override the vendor's wife relationship to use App\Models\Person (people table).
     */
    #[\Override]
    public function wife(): HasOne
    {
        return $this->hasOne(Person::class, 'id', 'wife_id');
    }

    /**
     * Override the vendor's children relationship to use App\Models\Person.
     *
     * The vendor Family::children() points at the vendor Person, which extends
     * plain Model — no SoftDeletes, no BelongsToTenant. So a family's children
     * included soft-deleted people and ignored the tenant scope. App\Models\Person
     * carries both, mirroring the husband()/wife()/events() overrides above.
     */
    #[\Override]
    public function children(): HasMany
    {
        return $this->hasMany(Person::class, 'child_in_family_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
