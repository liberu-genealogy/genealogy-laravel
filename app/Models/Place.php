<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Place extends \FamilyTree365\LaravelGedcom\Models\Place
{
    use HasFactory, BelongsToTenant;
}
