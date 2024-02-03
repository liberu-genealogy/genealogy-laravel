<?php

namespace App\Models;

use App\Traits\TenantConnectionResolver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use LaravelLiberu\Tables\Traits\TableCache;

class PersonName extends \FamilyTree365\LaravelGedcom\Models\PersonName
{
    use TableCache, HasFactory, TenantConnectionResolver;
}
