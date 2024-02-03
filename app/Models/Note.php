<?php

namespace App\Models;

use App\Traits\TenantConnectionResolver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use LaravelLiberu\Tables\Traits\TableCache;

class Note extends \FamilyTree365\LaravelGedcom\Models\Note
{
    use HasFactory, TableCache, TenantConnectionResolver;
}
