<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SourceRefEven extends \FamilyTree365\LaravelGedcom\Models\SourceRefEven
{
    use HasFactory, BelongsToTenant;
}
