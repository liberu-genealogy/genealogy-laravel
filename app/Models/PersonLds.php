<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class PersonLds extends \FamilyTree365\LaravelGedcom\Models\PersonLds
{
    use HasFactory;

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
