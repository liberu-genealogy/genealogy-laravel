<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SourceData extends \FamilyTree365\LaravelGedcom\Models\SourceData
{
    use HasFactory;
    use BelongsToTenant;
}
