<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ImportJob extends \FamilyTree365\LaravelGedcom\Models\ImportJob
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'slug',
        'status',
        'progress',
        'error_message',
        'people_imported',
        'families_imported',
    ];
}