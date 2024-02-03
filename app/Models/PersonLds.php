<?php

namespace App\Models;

use App\Traits\TenantConnectionResolver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use LaravelLiberu\Tables\Traits\TableCache;

class PersonLds extends \FamilyTree365\LaravelGedcom\Models\PersonLds
{
    use TableCache, HasFactory, TenantConnectionResolver;
}
