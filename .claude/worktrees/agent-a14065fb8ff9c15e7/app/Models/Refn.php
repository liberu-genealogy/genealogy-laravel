<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Refn extends \FamilyTree365\LaravelGedcom\Models\Refn
{
    use HasFactory;
    use BelongsToTenant;
}
