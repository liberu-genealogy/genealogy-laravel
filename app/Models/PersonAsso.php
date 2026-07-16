<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class PersonAsso extends \FamilyTree365\LaravelGedcom\Models\PersonAsso
{
    use BelongsToTenant;
    use HasFactory;
    use SoftDeletes;
}
