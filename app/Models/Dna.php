<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dna extends Model
{
    use BelongsToTenant;
    use HasFactory;

    #[\Override]
    protected $fillable = [
        'name',
        'file_name',
        'variable_name',
        'user_id',
        'consent_given',
        'consent_given_at',
    ];

    protected function casts(): array
    {
        return [
            'consent_given' => 'boolean',
            'consent_given_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /** True only when the owner has consented to storage + matching. */
    public function hasConsent(): bool
    {
        return (bool) $this->consent_given;
    }

    /** Set consent and stamp the moment it was given. */
    public function giveConsent(): void
    {
        $this->consent_given = true;
        $this->consent_given_at = now();
    }

    public function scopeConsented(Builder $query): Builder
    {
        return $query->where('consent_given', true);
    }
}
