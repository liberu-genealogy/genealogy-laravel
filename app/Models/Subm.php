<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subm extends \FamilyTree365\LaravelGedcom\Models\Subm
{
    use HasFactory;

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
