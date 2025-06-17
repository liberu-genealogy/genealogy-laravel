<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// use Laravel\Scout\Searchable;

//TODO: investigate why migration doesn't exist
class Gedcom extends Model
{
    use HasFactory;
    // use HasFactory;, Searchable;

    // protected $connection = 'landlord';

    protected $fillable = [
        'filename',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
        ];
    }
}