<?php

namespace App\Traits;

use App\Models\Team;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToTenant
{
    protected static function booted(): void
    {
        static::addGlobalScope('team', function (Builder $query) {
            if (auth()->check()) {
                $query->where('team_id', static::getTenantId());
            }
        });

        static::creating(function ($model) {
            $model->team_id = static::getTenantId();
        });
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    private static function getTenantId()
    {
        return auth()->user()->currentTeam->id ?? null;
    }
}
