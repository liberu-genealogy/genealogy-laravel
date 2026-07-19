<?php

namespace App\Traits;

use App\Models\Team;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

trait BelongsToTenant
{
    protected static function booted(): void
    {
        static::addGlobalScope('team', function (Builder $query): void {
            // Only apply scope when a tenant is available and the model's table has a team_id column
            if (! auth()->check()) {
                return;
            }
            $tenantId = static::getTenantId();
            if (empty($tenantId)) {
                return;
            }

            $table = $query->getModel()->getTable();
            if (Schema::hasColumn($table, 'team_id')) {
                $query->where($table.'.team_id', $tenantId);
            }
        });

        static::creating(function ($model): void {
            // Only auto-assign team_id when the record doesn't already have one set
            if (! empty($model->team_id)) {
                return;
            }

            $tenantId = static::getTenantId();
            if (empty($tenantId)) {
                return;
            }

            $table = $model->getTable();
            if (Schema::hasColumn($table, 'team_id')) {
                $model->team_id = $tenantId;
            }
        });
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Protected, not private: booted() reaches this through static::, so late static
     * binding resolves it against the model using the trait. A private method is
     * bound to its declaring scope, which makes that call unsafe the moment a model
     * using this trait is subclassed.
     */
    protected static function getTenantId(): ?int
    {
        // The tenant being viewed, falling back to the user's stored team where
        // there is no panel context — console commands and queued jobs.
        //
        // This read the stored team alone, which agreed with the URL only
        // because the SwitchTeam listener had already persisted it. That is an
        // unnamed dependency between a global scope and an event listener: if
        // the listener is removed, reordered, or fails to fire, scoping
        // silently reverts to whatever team was last stored and nothing fails.
        return Filament::getTenant()?->getKey() ?? auth()->user()?->currentTeam?->id;
    }
}
