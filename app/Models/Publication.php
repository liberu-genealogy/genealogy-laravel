<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Publication extends \FamilyTree365\LaravelGedcom\Models\Publication
{
    use HasFactory;
    use BelongsToTenant;
}
