<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use FamilyTree365\LaravelGedcom\Observers\EventActionsObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FamilyEvent extends \FamilyTree365\LaravelGedcom\Models\FamilyEvent
{
    use HasFactory;
    use BelongsToTenant;

    public static function boot(): void
    {
        static::bootTraits();
    }

    protected static function booted(): void
    {
        static::observe(new EventActionsObserver());
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
