<?php

namespace App\Models;

use App\Traits\TenantConnectionResolver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use LaravelLiberu\Tables\Traits\TableCache;

class Publication extends \FamilyTree365\LaravelGedcom\Models\Publication
{
    use TableCache, HasFactory, TenantConnectionResolver;
}
