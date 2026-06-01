<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FamilyEvent extends \FamilyTree365\LaravelGedcom\Models\FamilyEvent
{
//
    use HasFactory;
    use BelongsToTenant;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
