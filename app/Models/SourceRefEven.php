<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class SourceRefEven extends \FamilyTree365\LaravelGedcom\Models\SourceRefEven
{
    use HasFactory;

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
