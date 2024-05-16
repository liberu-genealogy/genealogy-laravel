<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Publication extends \FamilyTree365\LaravelGedcom\Models\Publication
{
    use HasFactory;

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
