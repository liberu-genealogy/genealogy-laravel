<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TeamInvitation;

class Team extends Model {

    protected $fillable = [
        'id',
        'name',
    ];

    public function invitations()
    {
        return $this->hasMany(TeamInvitation::class);
    }

} 
