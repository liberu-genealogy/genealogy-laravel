<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PersonSubm extends \FamilyTree365\LaravelGedcom\Models\PersonSubm
{
    use HasFactory;
    use BelongsToTenant;
}
