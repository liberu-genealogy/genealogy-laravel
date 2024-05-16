<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subm extends \FamilyTree365\LaravelGedcom\Models\Subm
{
    use HasFactory, BelongsToTenant;
}
