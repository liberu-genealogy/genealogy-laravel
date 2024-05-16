<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PersonNameFone extends \FamilyTree365\LaravelGedcom\Models\PersonNameFone
{
    use HasFactory, BelongsToTenant;
}
