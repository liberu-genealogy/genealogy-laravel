<?php

namespace App\Models;

use App\Traits\TenantConnectionResolver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use LaravelLiberu\Tables\Traits\TableCache;

class PersonNameRomn extends \FamilyTree365\LaravelGedcom\Models\PersonNameRomn
{
    use TableCache, HasFactory, TenantConnectionResolver;
}
