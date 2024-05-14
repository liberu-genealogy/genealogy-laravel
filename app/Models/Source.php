<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Source extends \FamilyTree365\LaravelGedcom\Models\Source
{
    use HasFactory;

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

}
