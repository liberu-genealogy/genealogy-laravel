<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class PersonName extends \FamilyTree365\LaravelGedcom\Models\PersonName
{
    use HasFactory;

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
