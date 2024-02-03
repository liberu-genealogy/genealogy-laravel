<?php

namespace App\Models;

use App\Traits\TenantConnectionResolver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use LaravelLiberu\Tables\Traits\TableCache;

class PersonAsso extends \FamilyTree365\LaravelGedcom\Models\PersonAsso
{
    use TableCache, HasFactory, TenantConnectionResolver;
}
