<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TeamInvitation;
use App\Models\User;

class Team extends Model
{
    protected $fillable = [
        'id',
        'name',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function invitations()
    {
        return $this->hasMany(TeamInvitation::class);
    }
}
