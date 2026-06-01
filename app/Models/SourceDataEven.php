<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SourceDataEven extends \FamilyTree365\LaravelGedcom\Models\SourceDataEven
{
    use HasFactory;
    use BelongsToTenant;
}
