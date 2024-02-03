<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class DnaMatching extends Model
{
    

    protected $fillable = [
        'file1',
        'file2',
        'image',
        'total_shared_cm',
        'largest_cm_segment',
        'match_id',
    ];
}
