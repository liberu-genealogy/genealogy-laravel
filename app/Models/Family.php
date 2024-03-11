<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Person;

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
        
    }
}
