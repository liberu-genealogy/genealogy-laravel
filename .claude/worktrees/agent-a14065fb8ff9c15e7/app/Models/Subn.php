<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subn extends \FamilyTree365\LaravelGedcom\Models\Subn
{
    use HasFactory;
    use BelongsToTenant;
}
