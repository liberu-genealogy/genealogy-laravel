<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Dna extends Model
{
    

    protected $fillable = [
        'name',
        'file_name',
        'variable_name',
        'user_id',
    ];
}
