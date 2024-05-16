<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class SourceDataEven extends \FamilyTree365\LaravelGedcom\Models\SourceDataEven
{
    use HasFactory;

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
