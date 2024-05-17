<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PersonName extends \FamilyTree365\LaravelGedcom\Models\PersonName
{
    use HasFactory, BelongsToTenant;
}
