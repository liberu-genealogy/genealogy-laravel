<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Represents a family unit in a genealogy context.
 *
 * This class extends the Family model from the LaravelGedcom package and provides relationships and functionality
 * to work with husband, wife, and children within a family structure as defined in a genealogical dataset.
 */
class Family extends \FamilyTree365\LaravelGedcom\Models\Family
{
    use HasFactory;

    public function husband()
    {
        return $this->belongsTo(Person::class, 'husband_id');
    }

    public function wife()
    {
        return $this->belongsTo(Person::class, 'wife_id');
    }

    public function wife()
    {
        return $this->belongsTo(Person::class, 'wife_id');
    }

    /**
     * Get the children of the family.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(Person::class, 'child_in_family_id');
    }
}
