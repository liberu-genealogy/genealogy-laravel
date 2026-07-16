<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PersonAnci extends \FamilyTree365\LaravelGedcom\Models\PersonAnci
{
    use BelongsToTenant;
    use HasFactory;
}
