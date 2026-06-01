<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SourceRepo extends \FamilyTree365\LaravelGedcom\Models\SourceRepo
{
    use HasFactory;
    use BelongsToTenant;
}
