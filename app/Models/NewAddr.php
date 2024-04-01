<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewAddr extends Model
{
    use HasFactory;

    protected $fillable = [
        'adr1',
        'adr2',
        'city',
        'state',
        'post',
        'ctry',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
