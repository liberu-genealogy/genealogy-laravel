<?php

namespace App\Models;

use App\Traits\TenantConnectionResolver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use LaravelLiberu\Tables\Traits\TableCache;

class Family extends \FamilyTree365\LaravelGedcom\Models\Family
{
    use TableCache, HasFactory;
    use TenantConnectionResolver;
}
