<?php

namespace App\Traits;

use App\Models\Team;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToTenant
{
    protected static function booted(): void
    {
        static::addGlobalScope('team', function (Builder $query) {
            if (auth()->check() && !app()->runningInConsole() && !request()->has('cross_tenant')) {
                $query->where('team_id', static::getTenantId());
            }
        });

        static::creating(function ($model) {
            if (!$model->team_id) {
                $model->team_id = static::getTenantId();
            }
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

    public function scopeCrossTenant($query)
    {
        return $query->withoutGlobalScope('team');
    }
}
