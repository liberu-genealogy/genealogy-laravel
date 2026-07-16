<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PersonNameRomn extends \FamilyTree365\LaravelGedcom\Models\PersonNameRomn
{
    use BelongsToTenant;
    use HasFactory;
}
