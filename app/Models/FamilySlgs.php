<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FamilySlgs extends \FamilyTree365\LaravelGedcom\Models\FamilySlgs
{
//
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
