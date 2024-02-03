<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;

class Avatar extends \LaravelLiberu\Avatars\Models\Avatar
{
    use HasFactory;
    use TenantConnectionResolver;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
