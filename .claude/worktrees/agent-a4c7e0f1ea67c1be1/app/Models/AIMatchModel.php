<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AIMatchModel extends Model
{
    #[\Override]
    protected $table = 'ai_match_models';

    #[\Override]
    protected $fillable = [
        'name',
        'weights',
    ];

    #[\Override]
    protected $casts = [
        'weights' => 'array',
    ];
}
