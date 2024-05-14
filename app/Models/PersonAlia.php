<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class PersonAlia extends \FamilyTree365\LaravelGedcom\Models\PersonAlia
{
    use HasFactory;

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
