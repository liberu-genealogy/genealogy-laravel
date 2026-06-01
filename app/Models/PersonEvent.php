<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\BelongsToTenant;
use FamilyTree365\LaravelGedcom\Observers\EventActionsObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PersonEvent extends \FamilyTree365\LaravelGedcom\Models\PersonEvent
{
    use HasFactory;
    use BelongsToTenant;

    public static function boot(): void
    {
        // Use static:: (forwarding call) so bootTraits() registers trait initializers
        // for this class, not the base Model class. Skip vendor's boot to avoid double
        // observer registration; the observer is registered in booted() instead.
        static::bootTraits();
    }

    protected static function booted(): void
    {
        static::observe(new EventActionsObserver());
    }
}
