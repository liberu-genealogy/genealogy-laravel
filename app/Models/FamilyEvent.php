<?php

namespace App\Models;

use App\Traits\TenantConnectionResolver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use LaravelLiberu\Tables\Traits\TableCache;

class FamilyEvent extends \FamilyTree365\LaravelGedcom\Models\FamilyEvent
{
//    use TenantConnectionResolver;
    use TableCache, HasFactory, TenantConnectionResolver;
}
