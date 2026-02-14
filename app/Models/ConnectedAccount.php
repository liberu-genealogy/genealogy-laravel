<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use JoelButcher\Socialstream\ConnectedAccount as SocialstreamConnectedAccount;
use JoelButcher\Socialstream\Events\ConnectedAccountCreated;
use JoelButcher\Socialstream\Events\ConnectedAccountDeleted;
use JoelButcher\Socialstream\Events\ConnectedAccountUpdated;

class ConnectedAccount extends SocialstreamConnectedAccount
{
    use HasFactory;
    use HasTimestamps;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'provider',
        'provider_id',
        'name',
        'nickname',
        'email',
        'avatar_path',
        'token',
        'secret',
        'refresh_token',
        'expires_at',
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => ConnectedAccountCreated::class,
        'updated' => ConnectedAccountUpdated::class,
        'deleted' => ConnectedAccountDeleted::class,
    ];
    /**
     * The attributes that should be cast.
     *
     * @return array
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'expires_at' => 'datetime',
            'enable_family_matching' => 'boolean',
            'cached_profile_data' => 'array',
            'last_synced_at' => 'datetime',
        ];
    }

    /**
     * Get the social family connections for this account.
     */
    public function socialFamilyConnections(): HasMany
    {
        return $this->hasMany(\App\Models\SocialFamilyConnection::class);
    }
}
