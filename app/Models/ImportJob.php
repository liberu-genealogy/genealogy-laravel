<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ImportJob extends \FamilyTree365\LaravelGedcom\Models\ImportJob
{
    use BelongsToTenant;
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'team_id',
        'user_id',
        'slug',
        'status',
        'progress',
        'error_message',
        'people_imported',
        'families_imported',
    ];
}