<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Refn extends \FamilyTree365\LaravelGedcom\Models\Refn
{
    use HasFactory;

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
