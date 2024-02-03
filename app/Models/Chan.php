<?php

namespace App\Models;

use App\Traits\TenantConnectionResolver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use LaravelLiberu\Tables\Traits\TableCache;

class Chan extends \FamilyTree365\LaravelGedcom\Models\Chan
{
    use TableCache, HasFactory, TenantConnectionResolver;
}
