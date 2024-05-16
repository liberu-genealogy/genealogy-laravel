<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonAnci extends \FamilyTree365\LaravelGedcom\Models\PersonAnci
{
    use HasFactory;

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

}
