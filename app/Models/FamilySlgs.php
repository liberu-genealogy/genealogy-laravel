<?php

namespace App\Models;

use App\Traits\TenantConnectionResolver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use LaravelLiberu\Tables\Traits\TableCache;

class FamilySlgs extends \FamilyTree365\LaravelGedcom\Models\FamilySlgs
{
//    use TenantConnectionResolver;
    use TableCache, HasFactory, TenantConnectionResolver;
}
