<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class SourceRepo extends \FamilyTree365\LaravelGedcom\Models\SourceRepo
{
    use HasFactory;

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
