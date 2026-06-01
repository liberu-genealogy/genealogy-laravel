<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subm extends \FamilyTree365\LaravelGedcom\Models\Subm
{
    use HasFactory;
    use BelongsToTenant;
}
