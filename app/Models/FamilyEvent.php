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
        // Call base Model::boot() directly to bypass vendor's observe() during boot,
        // which causes circular boot issues in Laravel 13 (observe() creates new static instance)
        \Illuminate\Database\Eloquent\Model::boot();
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
