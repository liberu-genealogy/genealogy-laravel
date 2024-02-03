<?php

namespace App\Models;

use App\Traits\TenantConnectionResolver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use LaravelLiberu\Tables\Traits\TableCache;

class SourceRef extends \FamilyTree365\LaravelGedcom\Models\SourceRef
{
    use TableCache, HasFactory, TenantConnectionResolver;
}
