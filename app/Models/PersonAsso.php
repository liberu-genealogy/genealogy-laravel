<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PersonAsso extends \FamilyTree365\LaravelGedcom\Models\PersonAsso
{
    use HasFactory;
    use BelongsToTenant;
}
