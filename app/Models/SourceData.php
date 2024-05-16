<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class SourceData extends \FamilyTree365\LaravelGedcom\Models\SourceData
{
    use HasFactory;

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
