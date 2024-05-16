<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class SourceRef extends \FamilyTree365\LaravelGedcom\Models\SourceRef
{
    use HasFactory;

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
