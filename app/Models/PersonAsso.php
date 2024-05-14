<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class PersonAsso extends \FamilyTree365\LaravelGedcom\Models\PersonAsso
{
    use HasFactory;

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
