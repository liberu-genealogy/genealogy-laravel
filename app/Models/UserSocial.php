<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSocial extends Model
{
    #[\Override]
    protected $table = 'user_social';

    #[\Override]
    protected $fillable = [
        'user_id',
        'social_id',
        'service',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
