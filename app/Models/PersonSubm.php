<?php

namespace App\Models;

use App\Traits\TenantConnectionResolver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use LaravelLiberu\Tables\Traits\TableCache;

class PersonSubm extends \FamilyTree365\LaravelGedcom\Models\PersonSubm
{
    use TableCache, HasFactory, TenantConnectionResolver;
}
