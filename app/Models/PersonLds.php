<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PersonLds extends \FamilyTree365\LaravelGedcom\Models\PersonLds
{
    use HasFactory;
    use BelongsToTenant;
}
