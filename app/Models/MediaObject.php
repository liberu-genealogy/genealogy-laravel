<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class MediaObject extends \FamilyTree365\LaravelGedcom\Models\MediaObject
{
    use HasFactory;

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
