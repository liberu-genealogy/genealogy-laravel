<?php

namespace App\Traits;

use App\Models\Team;
use Filament\Facades\Filament;
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
        $tenant = Filament::getTenant();
        return isset($tenant) ? $tenant->id : null;
    }

}
