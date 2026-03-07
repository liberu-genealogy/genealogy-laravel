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
    use \App\Traits\HasResearchChecklists;

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
     * Override the vendor's husband relationship to use App\Models\Person (persons table).
     */
    public function husband(): HasOne
    {
        return $this->hasOne(Person::class, 'id', 'husband_id');
    }

    /**
     * Override the vendor's wife relationship to use App\Models\Person (persons table).
     */
    public function wife(): HasOne
    {
        return $this->hasOne(Person::class, 'id', 'wife_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
