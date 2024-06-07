<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MediaObject extends \FamilyTree365\LaravelGedcom\Models\MediaObject
{
    use HasFactory;
    use BelongsToTenant;
}
