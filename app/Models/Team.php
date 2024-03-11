<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TeamInvitation;

class Team extends Model {

    /**
     * Represents a team in the application.
     */

    protected $fillable = [
        'id',
        'name',
    ];

        /**
     * Get the invitations associated with the team.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invitations()
    {
        return $this->hasMany(TeamInvitation::class);
    }

} 
