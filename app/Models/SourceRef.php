<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SourceRef extends \FamilyTree365\LaravelGedcom\Models\SourceRef
{
    use HasFactory, BelongsToTenant;
}
