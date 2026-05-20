<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AIMatchModel extends Model
{
    protected $table = 'ai_match_models';

    protected $fillable = [
        'name',
        'weights',
    ];

    protected $casts = [
        'weights' => 'array',
    ];
}
