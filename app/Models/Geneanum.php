<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Laravel\Scout\Searchable;

//TODO: investigate why migration doesn't exist
class Geneanum extends Model
{
    use HasFactory;
    // use HasFactory;, Searchable;

    // protected $connection = 'landlord';

    protected $fillable = [
        'remote_id',
        'data',
        'area',
        'db_name',
    ];

    protected $casts = [
        'data' => 'array',
    ];
}
