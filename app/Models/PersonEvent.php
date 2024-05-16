<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class PersonEvent extends \FamilyTree365\LaravelGedcom\Models\PersonEvent
{
    use HasFactory;

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
