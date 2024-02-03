<?php

namespace App\Models;

use App\Traits\TenantConnectionResolver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use LaravelLiberu\Tables\Traits\TableCache;

class Source extends \FamilyTree365\LaravelGedcom\Models\Source
{
    use TableCache, HasFactory, TenantConnectionResolver;
}
