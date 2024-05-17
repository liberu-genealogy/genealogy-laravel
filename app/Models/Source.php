<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Source extends \FamilyTree365\LaravelGedcom\Models\Source
{
    use HasFactory, BelongsToTenant;

}
