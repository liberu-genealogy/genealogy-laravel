<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class PersonNameFone extends \FamilyTree365\LaravelGedcom\Models\PersonNameFone
{
    use HasFactory;

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
