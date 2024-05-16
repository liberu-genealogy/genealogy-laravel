<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Addr extends Model
{
    use HasFactory, BelongsToTenant;

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


}
