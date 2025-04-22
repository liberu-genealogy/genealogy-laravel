<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PersonAlia extends \FamilyTree365\LaravelGedcom\Models\PersonAlia
{
    use HasFactory;
    use BelongsToTenant;
}
