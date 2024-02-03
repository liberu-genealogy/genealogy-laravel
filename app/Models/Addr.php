<?php

namespace App\Models;

use App\Traits\TenantConnectionResolver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use LaravelLiberu\Tables\Traits\TableCache;

class Addr extends \FamilyTree365\LaravelGedcom\Models\Addr
{
    use TableCache, HasFactory, TenantConnectionResolver;
}
